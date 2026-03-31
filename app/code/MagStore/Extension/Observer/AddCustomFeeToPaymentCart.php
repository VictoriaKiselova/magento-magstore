<?php

namespace MagStore\Extension\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Payment\Model\Cart;

class AddCustomFeeToPaymentCart implements ObserverInterface
{
    public function execute(Observer $observer): void
    {
        $cart = $observer->getEvent()->getCart();
        if (!$cart instanceof Cart) {
            return;
        }

        $salesModel = $cart->getSalesModel();
        $amount = (float) $salesModel->getDataUsingMethod('base_custom_fee');

        if ($amount <= 0.0001) {
            return;
        }

        $cart->addCustomItem((string) __('Custom Fee'), 1, $amount, 'custom_fee');
    }
}
