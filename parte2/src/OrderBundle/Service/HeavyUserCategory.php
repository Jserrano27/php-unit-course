<?php


namespace OrderBundle\Service;


class HeavyUserCategory implements CustomerCategoryInterface
{
    public function isEligible($customer)
    {
        return
            $customer->getTotalOrders() >= 50 &&
            $customer->getTotalRatings() >= 10 &&
            $customer->getTotalRecommendations() >= 1;
    }

    public function getCategoryName()
    {
        return CustomerCategoryService::CATEGORY_HEAVY_USER;
    }
}