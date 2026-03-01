<?php

namespace MagStore\ProductFeatures\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Review\Model\Review\SummaryFactory;
use Magento\Catalog\Model\Product;

class ReviewSummary implements ArgumentInterface
{
    protected $summaryFactory;

    public function __construct(SummaryFactory $summaryFactory)
    {
        $this->summaryFactory = $summaryFactory;
    }

    public function getReviewSummaryData(Product $product): array
    {
        $summary = $this->summaryFactory->create()
            ->setStoreId($product->getStoreId())
            ->load($product->getId());

        return [
            'rating_percent' => $summary->getRatingSummary() ?? 0,
            'reviews_count' => $summary->getReviewsCount() ?? 0
        ];
    }
}
