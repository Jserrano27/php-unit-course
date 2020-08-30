<?php

namespace OrderBundle\Test\Service;

use OrderBundle\Entity\Customer;
use PHPUnit\Framework\TestCase;
use OrderBundle\Service\CustomerCategoryService;

class CustomerCategoryServiceTest extends TestCase
{
    /**
     * TDD para nova implementaçao de um serviço que categorize os usuarios em 4 grupos com fins de marketing:
     * CATEGORY_NEW_USER: 0 pedidos, 0 avaliaçoes, 0 recomendaçoes
     * CATEGORY_LIGHT_USER: 5 pedidos, 1 avaliaçao, 0 recomendaçoes
     * CATEGORY_MEDIUM_USER: 20 pedidos, 5 avaliaçoes, 1 recomendaçao
     * CATEGORY_HEAVY_USER: 50 pedidos, 10 avaliaçoes, 5 recomendaçoes
     */

    /**
     * @test
     */
    public function customerShouldBeNewUser()
    {
        $customerCategoryService = new CustomerCategoryService();

        $customer = new Customer();

        $usageCategory = $customerCategoryService->getUsageCategory($customer);

        $this->assertEquals(CustomerCategoryService::CATEGORY_NEW_USER, $usageCategory);
    }
}