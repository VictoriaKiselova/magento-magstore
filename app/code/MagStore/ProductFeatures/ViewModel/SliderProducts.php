<?php

namespace MagStore\ProductFeatures\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Helper\Image;

class SliderProducts implements ArgumentInterface
{
    private $productRepository;
    private $imageHelper;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        Image $imageHelper
    ) {
        $this->productRepository = $productRepository;
        $this->imageHelper = $imageHelper;
    }

    public function getCarouselProducts(): array
    {
        $skus = ['WJ10', 'WH10', 'WS10', 'WJ11', 'WJ04', 'WJ01', 'WJ09', 'WH07'];
        $products = [];

        foreach ($skus as $sku) {
            try {
                $products[] = $this->productRepository->get($sku);
            } catch (\Exception $e) {
                continue;
            }
        }
        return $products;
    }

    public function getImageUrl($product): string
    {
        return $this->imageHelper->init($product, 'product_base_image')->getUrl();
    }
}
