<?php
declare(strict_types=1);

namespace App\Tests\Unit\UserImporter;

use App\Tests\DummyServices\DummyCustomerService;
use App\UserImporter\DataProvider\UserDataProviderInterface;
use App\UserImporter\DTO\User;
use App\UserImporter\DTO\UserCollection;
use App\UserImporter\UserImporter;
use PHPUnit\Framework\TestCase;

class UserImporterTest extends TestCase
{
    private DummyCustomerService $customerService;

    protected function setUp(): void
    {
        $this->customerService = new DummyCustomerService();
    }

    public function testImportUser(): void
    {
        $dataProviderMock = $this->createMock(UserDataProviderInterface::class);

        $dataProviderMock
            ->method('getOneUser')
            ->willReturn(
                $this->createMock(User::class)
            );

        $userImporter = new UserImporter($this->customerService, $dataProviderMock);

        $userImporter->importUser();

        $customers = $this->customerService->getSavedCustomers();

        $this->assertCount(1, $customers);
    }

    public function testImportBatch(): void
    {
        $userCollection = new UserCollection(
            [
                $this->createMock(User::class),
                $this->createMock(User::class)
            ]
        );

        $dataProviderMock = $this->createMock(UserDataProviderInterface::class);

        $dataProviderMock
            ->method('getBatch')
            ->willReturn($userCollection);

        $userImporter = new UserImporter($this->customerService, $dataProviderMock);

        $userImporter->importBatch(2);

        $customers = $this->customerService->getSavedCustomers();

        $this->assertCount(2, $customers);
    }
}