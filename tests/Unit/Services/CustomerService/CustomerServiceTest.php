<?php
declare(strict_types=1);

namespace App\Tests\Unit\Services\CustomerService;

use App\Entity\Collection\CustomerCollection;
use App\Entity\Customer;
use App\Repository\CustomerRepository;
use App\Services\CustomerService\CustomerService;
use App\Services\CustomerService\Exception\CustomerNotFoundException;
use App\Services\CustomerService\Exception\CustomerServiceException;
use Exception;
use Knp\Component\Pager\PaginatorInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\Test\TestLogger;

class CustomerServiceTest extends TestCase
{
    /**
     * @var CustomerRepository|mixed|MockObject
     */
    private $repositoryMock;

    /**
     * @var PaginatorInterface|mixed|MockObject
     */
    private $paginatorMock;

    private TestLogger $logger;

    private CustomerService $customerService;

    protected function setUp(): void
    {
        $this->repositoryMock = $this->createMock(CustomerRepository::class);
        $this->paginatorMock  = $this->createMock(PaginatorInterface::class);
        $this->logger         = new TestLogger();

        $this->customerService = new CustomerService(
            $this->repositoryMock,
            $this->paginatorMock,
            $this->logger
        );
    }

    public function testGetCustomerByIdNotFound(): void
    {
        $this->repositoryMock->method('find')->willReturn(null);

        $this->expectException(CustomerNotFoundException::class);
        $this->customerService->getCustomerById(1);
    }

    public function testGetCustomerById(): void
    {
        $customerMock = $this->createMock(Customer::class);

        $this->repositoryMock->method('find')->willReturn($customerMock);

        $customer = $this->customerService->getCustomerById(1);

        $this->assertSame($customerMock, $customer);
    }

    public function testFindCustomerByEmail(): void
    {
        $customerMock = $this->createMock(Customer::class);

        $this->repositoryMock->method('findOneByEmail')->willReturn($customerMock);

        $customer = $this->customerService->findCustomerByEmail('email');

        $this->assertSame($customerMock, $customer);
    }

    public function testFindCustomerByEmailException(): void
    {
        $this->repositoryMock->method('findOneByEmail')->willThrowException(new Exception());

        $this->expectException(CustomerServiceException::class);
        $this->customerService->findCustomerByEmail('email');

        $this->assertCount(1, $this->logger->records);
    }

    public function testSaveCustomerException(): void
    {
        $this->repositoryMock->method('save')->willThrowException(new Exception());

        $this->expectException(CustomerServiceException::class);
        $this->customerService->saveCustomer(
            $this->createMock(Customer::class)
        );

        $this->assertCount(1, $this->logger->records);
    }

    public function testSaveCustomerCollectionException(): void
    {
        $this->repositoryMock->method('saveCollection')->willThrowException(new Exception());

        $this->expectException(CustomerServiceException::class);
        $this->customerService->saveCustomerCollection(
            $this->createMock(CustomerCollection::class)
        );

        $this->assertCount(1, $this->logger->records);
    }
}