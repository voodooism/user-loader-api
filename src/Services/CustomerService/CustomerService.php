<?php
declare(strict_types=1);

namespace App\Services\CustomerService;

use App\Entity\Collection\CustomerCollection;
use App\Entity\Customer;
use App\Repository\CustomerRepository;
use App\Services\CustomerService\Exception\CustomerNotFoundException;
use App\Services\CustomerService\Exception\CustomerServiceException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Psr\Log\LoggerInterface;
use Throwable;

class CustomerService implements CustomerServiceInterface
{
    private CustomerRepository $customerRepository;

    private PaginatorInterface $paginator;

    private LoggerInterface $logger;

    public function __construct(
        CustomerRepository $customerRepository,
        PaginatorInterface $paginator,
        LoggerInterface    $logger
    ) {
        $this->customerRepository = $customerRepository;
        $this->paginator = $paginator;
        $this->logger = $logger;
    }

    /**
     * @throws CustomerNotFoundException
     */
    public function getCustomerById(int $id): Customer
    {
        $customer = $this->customerRepository->find($id);

        if ($customer === null) {
            throw new CustomerNotFoundException(
                sprintf('Customer with id: `%s` not found', $id)
            );
        }

        return $customer;
    }

    public function getCustomersPagination(int $page, int $limit): PaginationInterface
    {
        $query = $this->customerRepository->getQueryForPagination();
        $pagination = $this->paginator->paginate($query, $page, $limit);

        return $pagination;
    }

    /**
     * @throws CustomerServiceException
     */
    public function findCustomerByEmail(string $email): ?Customer
    {
        try {
            $customer = $this->customerRepository->findOneByEmail($email);
        } catch (Throwable $t) {
            $message = sprintf(
                'Something went wrong when trying to find customer by email. Error message: `%s`',
                $t->getMessage()
            );

            $this->logger->critical($message);

            throw new CustomerServiceException($message, 0, $t);
        }

        return $customer;
    }

    /**
     * @throws CustomerServiceException
     */
    public function saveCustomer(Customer $customer): void
    {
        try {
            $this->customerRepository->save($customer);
        } catch (Throwable $t) {
            $message = sprintf(
                'Something went wrong when trying to save customer. Error message: `%s`',
                $t->getMessage()
            );

            $this->logger->critical($message);

            throw new CustomerServiceException($message, 0, $t);
        }
    }

    /**
     * @throws CustomerServiceException
     */
    public function saveCustomerCollection(CustomerCollection $collection): void
    {
        try {
            $this->customerRepository->saveCollection($collection);
        } catch (Throwable $t) {
            $message = sprintf(
                'Something went wrong when trying to save customer collection. Error message: `%s`',
                $t->getMessage()
            );

            $this->logger->critical($message);

            throw new CustomerServiceException($message, 0, $t);
        }
    }
}
