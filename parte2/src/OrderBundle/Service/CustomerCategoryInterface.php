<?php


namespace OrderBundle\Service;


interface CustomerCategoryInterface
{
    public function isEligible($customer);

    public function getCategoryName();
}