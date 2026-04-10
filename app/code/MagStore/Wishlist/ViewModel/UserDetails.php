<?php

namespace MagStore\Wishlist\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Customer\Model\Session as CustomerSession;

class UserDetails implements ArgumentInterface
{
    private CustomerSession $customerSession;

    public function __construct(
        CustomerSession $customerSession
    )
    {
        $this->customerSession = $customerSession;
    }

    public function isLoggedIn(): bool
    {
        return $this->customerSession->isLoggedIn();
    }

    public function getCustomerName(): string
    {
        if (!$this->isLoggedIn()) {
            return '';
        }

        return (string)$this->customerSession->getCustomer()->getName();
    }

    public function getCustomerEmail(): string
    {
        if (!$this->isLoggedIn()) {
            return '';
        }

        return (string)$this->customerSession->getCustomer()->getEmail();
    }

    public function getCustomerId(): ?int
    {
        if (!$this->isLoggedIn()) {
            return null;
        }

        return (int)$this->customerSession->getCustomerId();
    }
}
