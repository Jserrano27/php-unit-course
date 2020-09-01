<?php


namespace OrderBundle\Service;


class MediumUserCategory implements CustomerCategoryInterface
{
    public function isEligible($customer)
    {
        return
            $customer->getTotalOrders() >= 20 &&
            $customer->getTotalRatings() >= 5 &&
            $customer->getTotalRecommendations() >= 1;
    }

    public function getCategoryName()
    {
        return CustomerCategoryService::CATEGORY_MEDIUM_USER;
    }
}