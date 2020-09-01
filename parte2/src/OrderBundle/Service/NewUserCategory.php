<?php


namespace OrderBundle\Service;


class NewUserCategory implements CustomerCategoryInterface
{
    public function isEligible($customer)
    {
        return true;
    }

    public function getCategoryName()
    {
        return CustomerCategoryService::CATEGORY_NEW_USER;
    }
}