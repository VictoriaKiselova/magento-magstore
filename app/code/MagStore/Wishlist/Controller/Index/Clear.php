<?php

namespace MagStore\Wishlist\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Wishlist\Model\WishlistFactory;

class Clear extends Action implements HttpPostActionInterface
{
    protected $wishlistFactory;

    public function __construct(
        Context $context,
        WishlistFactory $wishlistFactory
    ) {
        $this->wishlistFactory = $wishlistFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $wishlist = $this->_objectManager
            ->get(\Magento\Wishlist\Controller\WishlistProviderInterface::class)
            ->getWishlist();

        foreach ($wishlist->getItemCollection() as $item) {
            $item->delete();
        }

        $wishlist->save();

        return $this->_redirect('wishlist');
    }
}
