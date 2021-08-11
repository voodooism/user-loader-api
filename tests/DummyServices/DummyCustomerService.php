<?php
declare(strict_types=1);

namespace App\Tests\DummyServices;

use App\Entity\Collection\CustomerCollection;
use App\Entity\Customer;
use App\Services\CustomerService\CustomerServiceInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\Pagination\SlidingPagination;

class DummyCustomerService implements CustomerServiceInterface
{
    private array $savedCustomers = [];
    
    public function getCustomerById(int $id): Customer
    {
        return new Customer(
            '',
            '',
            '',
            '',
            '',
            '',
            ''
        );
    }

    public function getCustomersPagination(int $page, int $limit): PaginationInterface
    {
        return new SlidingPagination();
    }

    public function findCustomerByEmail(string $email): ?Customer
    {
        return null;
    }

    public function saveCustomer(Customer $customer): void
    {
        $this->savedCustomers[] = $customer;
    }

    public function saveCustomerCollection(CustomerCollection $collection): void
    {
        foreach ($collection as $customer) {
            $this->savedCustomers[] = $customer;
        }
    }
    
    public function getSavedCustomers(): array
    {
        return $this->savedCustomers;
    }
}