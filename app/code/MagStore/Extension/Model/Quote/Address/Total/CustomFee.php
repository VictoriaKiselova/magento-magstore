<?php

namespace MagStore\Extension\Model\Quote\Address\Total;

use Magento\Quote\Api\Data\ShippingAssignmentInterface;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Address\Total;
use Magento\Quote\Model\Quote\Address\Total\AbstractTotal;

class CustomFee extends AbstractTotal
{
    public const AMOUNT = 10.0;

    public function __construct()
    {
        $this->setCode('custom_fee');
    }

    public function collect(Quote $quote, ShippingAssignmentInterface $shippingAssignment, Total $total)
    {
        parent::collect($quote, $shippingAssignment, $total);

        if (!count($shippingAssignment->getItems())) {
            return $this;
        }

        $amount = self::AMOUNT;

        $total->setTotalAmount($this->getCode(), $amount);
        $total->setBaseTotalAmount($this->getCode(), $amount);

        $quote->setData('custom_fee', $amount);
        $quote->setData('base_custom_fee', $amount);
        $total->setData('custom_fee', $amount);
        $total->setData('base_custom_fee', $amount);

        return $this;
    }

    public function fetch(Quote $quote, Total $total): array
    {
        $amount = (float) $total->getData('custom_fee');

        if ($amount <= 0.0001) {
            return [];
        }

        return [
            'code' => $this->getCode(),
            'title' => __('Custom Fee'),
            'value' => $amount,
        ];
    }

    public function getLabel(): string
    {
        return (string) __('Custom Fee');
    }
}
