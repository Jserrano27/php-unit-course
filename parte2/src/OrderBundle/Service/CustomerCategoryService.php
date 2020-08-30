<?php

namespace OrderBundle\Service;

class CustomerCategoryService
{
    const CATEGORY_NEW_USER = 'new-user';

    public function getUsageCategory()
    {
        return self::CATEGORY_NEW_USER;
    }
}