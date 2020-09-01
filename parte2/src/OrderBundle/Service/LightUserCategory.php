<?php


namespace OrderBundle\Service;


class LightUserCategory implements CustomerCategoryInterface
{
    public function isEligible($customer)
    {
        return
            $customer->getTotalOrders() >= 5 &&
            $customer->getTotalRatings() >= 1;
    }

    public function getCategoryName()
    {
        return CustomerCategoryService::CATEGORY_LIGHT_USER;
    }
}