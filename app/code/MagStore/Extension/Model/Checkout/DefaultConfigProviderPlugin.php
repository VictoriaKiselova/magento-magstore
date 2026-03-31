<?php

declare(strict_types=1);

namespace MagStore\Extension\Model\Checkout;

use Magento\Checkout\Model\DefaultConfigProvider;
use Magento\Customer\Model\Session as CustomerSession;
use MagStore\Extension\Model\Checkout\ShippingAddressLimiter;

class DefaultConfigProviderPlugin
{
    public function __construct(
        private readonly CustomerSession $customerSession,
        private readonly ShippingAddressLimiter $shippingAddressLimiter
    ) {}

    public function afterGetConfig(DefaultConfigProvider $subject, array $result): array
    {
        if (!$this->customerSession->isLoggedIn()) {
            return $result;
        }

        if (
            empty($result['customerData']) ||
            empty($result['customerData']['addresses']) ||
            !is_array($result['customerData']['addresses'])
        ) {
            return $result;
        }

        $customerId = (int) $this->customerSession->getCustomerId();

        if ($customerId <= 0) {
            return $result;
        }

        $result['customerData']['addresses'] = $this->shippingAddressLimiter->limit(
            $result['customerData']['addresses'],
            $customerId,
            4
        );

        return $result;
    }
}
