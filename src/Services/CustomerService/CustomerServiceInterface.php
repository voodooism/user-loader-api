<?php

namespace App\Services\CustomerService;

use App\Entity\Collection\CustomerCollection;
use App\Entity\Customer;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface CustomerServiceInterface
{
    public function getCustomerById(int $id): Customer;

    public function getCustomersPagination(int $page, int $limit): PaginationInterface;

    public function findCustomerByEmail(string $email): ?Customer;

    public function saveCustomer(Customer $customer): void;

    public function saveCustomerCollection(CustomerCollection $collection): void;
}
