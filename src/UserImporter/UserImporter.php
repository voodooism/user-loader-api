<?php
declare(strict_types=1);

namespace App\UserImporter;

use App\Entity\Collection\CustomerCollection;
use App\Entity\Customer;
use App\Services\CustomerService\CustomerServiceInterface;
use App\Services\CustomerService\Exception\CustomerServiceException;
use App\UserImporter\DataProvider\UserDataProviderInterface;
use App\UserImporter\DTO\User;

class UserImporter implements UserImporterInterface
{
    private CustomerServiceInterface $customerService;

    private UserDataProviderInterface $dataProvider;

    public function __construct(CustomerServiceInterface $customerService, UserDataProviderInterface $dataProvider)
    {
        $this->customerService = $customerService;
        $this->dataProvider = $dataProvider;
    }

    /**
     * @throws CustomerServiceException
     */
    public function importUser(): void
    {
        $userDto = $this->dataProvider->getOneUser();

        $customer = $this->createOrUpdateCustomer($userDto);

        $this->customerService->saveCustomer($customer);
    }

    /**
     * @param int $count
     *
     * @throws CustomerServiceException
     */
    public function importBatch(int $count): void
    {
        $users = $this->dataProvider->getBatch($count);

        $customerCollection = new CustomerCollection();
        foreach ($users as $user) {
            $customer = $this->createOrUpdateCustomer($user);
            $customerCollection->add($customer);
        }

        $this->customerService->saveCustomerCollection($customerCollection);
    }

    /**
     * @throws CustomerServiceException
     */
    private function createOrUpdateCustomer(User $user): Customer
    {
        $customer = $this->customerService->findCustomerByEmail(
            $user->getEmail()
        );

        if ($customer !== null) {
            $customer->setFullName(
                $user->getFullName()
            );
            $customer->setEmail(
                $user->getEmail()
            );
            $customer->setCountry(
                $user->getCountry()
            );
            $customer->setUsername(
                $user->getUsername()
            );
            $customer->setGender(
                $user->getGender()
            );
            $customer->setCity(
                $user->getCity()
            );
            $customer->setPhone(
                $user->getPhone()
            );
        } else {
            $customer = new Customer(
                $user->getFullName(),
                $user->getEmail(),
                $user->getCountry(),
                $user->getUsername(),
                $user->getGender(),
                $user->getCity(),
                $user->getPhone()
            );
        }

        return $customer;
    }
}
