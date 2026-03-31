<?php

namespace MagStore\Extension\Model\Checkout;

class LayoutProcessorPlugin
{
    public function afterProcess(
        \Magento\Checkout\Block\Checkout\LayoutProcessor $subject,
        array $jsLayout
    ) {
        $shippingFields = &$jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress']['children']['shipping-address-fieldset']['children'];

        $streetPath = &$shippingFields['street']['children'];

        if (isset($streetPath[0])) {
            $streetPath[0]['validation'] = array_merge(
                $streetPath[0]['validation'] ?? [],
                [
                    'required-entry' => true,
                    'min_text_length' => 5,
                    'max_text_length' => 255,
                ]
            );
        }

        if (isset($streetPath[1])) {
            $streetPath[1]['validation'] = array_merge(
                $streetPath[1]['validation'] ?? [],
                [
                    'min_text_length' => 5,
                    'max_text_length' => 255,
                ]
            );
        }

        if (isset($shippingFields['firstname'])) {
            $shippingFields['firstname']['notice'] = __(
                'You cannot change your name once the account is created.'
            );
            $shippingFields['firstname']['additionalClasses'] = 'shipping-address-note shipping-address-firstname-note';
        }

        if (isset($shippingFields['lastname'])) {
            $shippingFields['lastname']['notice'] = __(
                'Enter the surname exactly as it should appear on the shipping label.'
            );
            $shippingFields['lastname']['additionalClasses'] = 'shipping-address-note shipping-address-lastname-note';
        }

        return $jsLayout;
    }
}
