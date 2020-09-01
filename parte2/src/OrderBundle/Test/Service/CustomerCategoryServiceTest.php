<?php

namespace OrderBundle\Test\Service;

use OrderBundle\Entity\Customer;
use OrderBundle\Service\HeavyUserCategory;
use OrderBundle\Service\LightUserCategory;
use OrderBundle\Service\MediumUserCategory;
use OrderBundle\Service\NewUserCategory;
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

    private $customerCategoryService;

    public function setUp()
    {
        $this->customerCategoryService = new CustomerCategoryService();
        $this->customerCategoryService->addCategory(new HeavyUserCategory());
        $this->customerCategoryService->addCategory(new MediumUserCategory());
        $this->customerCategoryService->addCategory(new LightUserCategory());
        $this->customerCategoryService->addCategory(new NewUserCategory());
    }

    /**
     * @test
     */
    public function customerShouldBeNewUser()
    {
        $customer = new Customer();

        $usageCategory = $this->customerCategoryService->getUsageCategory($customer);

        $this->assertEquals(CustomerCategoryService::CATEGORY_NEW_USER, $usageCategory);
    }

    /**
     * @test
     */
    public function customerShouldBeLightUser()
    {
        $customer = new Customer();
        $customer->setTotalOrders(5);
        $customer->setTotalRatings(1);

        $usageCategory = $this->customerCategoryService->getUsageCategory($customer);

        $this->assertEquals(CustomerCategoryService::CATEGORY_LIGHT_USER, $usageCategory);
    }

    /**
     * @test
     */
    public function customerShouldBeMediumUser()
    {
        $customer = new Customer();
        $customer->setTotalOrders(20);
        $customer->setTotalRatings(5);
        $customer->setTotalRecommendations(1);

        $usageCategory = $this->customerCategoryService->getUsageCategory($customer);

        $this->assertEquals(CustomerCategoryService::CATEGORY_MEDIUM_USER, $usageCategory);
    }

    /**
     * @test
     */
    public function customerShouldBeHeavyUser()
    {
        $customer = new Customer();
        $customer->setTotalOrders(50);
        $customer->setTotalRatings(10);
        $customer->setTotalRecommendations(5);

        $usageCategory = $this->customerCategoryService->getUsageCategory($customer);

        $this->assertEquals(CustomerCategoryService::CATEGORY_HEAVY_USER, $usageCategory);
    }
}