<?php

declare(strict_types=1);

namespace MagStore\Extension\Model\Checkout;

use Magento\Framework\App\ResourceConnection;
use Zend_Db_Expr;

class ShippingAddressLimiter
{
    private const LIMIT = 4;

    public function __construct(
        private readonly ResourceConnection $resourceConnection
    ) {}

    public function limit(array $addresses, int $customerId, int $limit = self::LIMIT): array
    {
        if ($customerId <= 0 || empty($addresses)) {
            return $addresses;
        }

        if (count($addresses) <= $limit) {
            return $addresses;
        }

        $availableIds = array_map('intval', array_keys($addresses));

        $recentUsedIds = $this->getRecentUsedAddressIds($customerId, $availableIds, $limit);
        $recentAddedIds = [];

        if (count($recentUsedIds) < $limit) {
            $recentAddedIds = $this->getRecentAddedAddressIds(
                $customerId,
                $availableIds,
                $recentUsedIds,
                $limit - count($recentUsedIds)
            );
        }

        $orderedIds = array_values(array_unique(array_merge($recentUsedIds, $recentAddedIds)));

        if (empty($orderedIds)) {
            return $this->takeLastAddedAddresses($addresses, $customerId, $limit);
        }

        $result = [];

        foreach ($orderedIds as $addressId) {
            if (isset($addresses[$addressId])) {
                $result[$addressId] = $addresses[$addressId];
            }
        }

        return $result;
    }

    private function getRecentUsedAddressIds(int $customerId, array $availableIds, int $limit): array
    {
        if (empty($availableIds) || $limit <= 0) {
            return [];
        }

        $connection = $this->resourceConnection->getConnection();
        $salesOrderAddressTable = $this->resourceConnection->getTableName('sales_order_address');
        $salesOrderTable = $this->resourceConnection->getTableName('sales_order');

        $select = $connection->select()
            ->from(
                ['soa' => $salesOrderAddressTable],
                [
                    'customer_address_id',
                    'last_used_at' => new Zend_Db_Expr('MAX(so.created_at)')
                ]
            )
            ->joinInner(
                ['so' => $salesOrderTable],
                'so.entity_id = soa.parent_id',
                []
            )
            ->where('so.customer_id = ?', $customerId)
            ->where('soa.address_type = ?', 'shipping')
            ->where('soa.customer_address_id IS NOT NULL')
            ->where('soa.customer_address_id IN (?)', $availableIds)
            ->group('soa.customer_address_id')
            ->order('last_used_at DESC')
            ->limit($limit);

        return array_map('intval', $connection->fetchCol($select));
    }

    private function getRecentAddedAddressIds(
        int $customerId,
        array $availableIds,
        array $excludeIds,
        int $limit
    ): array {
        if (empty($availableIds) || $limit <= 0) {
            return [];
        }

        $connection = $this->resourceConnection->getConnection();
        $customerAddressTable = $this->resourceConnection->getTableName('customer_address_entity');

        $select = $connection->select()
            ->from(['cae' => $customerAddressTable], ['entity_id'])
            ->where('cae.parent_id = ?', $customerId)
            ->where('cae.entity_id IN (?)', $availableIds)
            ->order('cae.created_at DESC')
            ->limit($limit);

        if (!empty($excludeIds)) {
            $select->where('cae.entity_id NOT IN (?)', $excludeIds);
        }

        return array_map('intval', $connection->fetchCol($select));
    }

    private function takeLastAddedAddresses(array $addresses, int $customerId, int $limit): array
    {
        $availableIds = array_map('intval', array_keys($addresses));
        $lastAddedIds = $this->getRecentAddedAddressIds($customerId, $availableIds, [], $limit);
        $result = [];

        foreach ($lastAddedIds as $addressId) {
            if (isset($addresses[$addressId])) {
                $result[$addressId] = $addresses[$addressId];
            }
        }

        return $result ?: array_slice($addresses, 0, $limit, true);
    }
}
