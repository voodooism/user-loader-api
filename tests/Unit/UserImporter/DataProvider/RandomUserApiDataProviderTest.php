<?php
declare(strict_types=1);

namespace App\Tests\Unit\UserImporter\DataProvider;

use App\UserImporter\DataProvider\RandomUserApiUserDataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Voodooism\RandomUser\Client;
use Voodooism\RandomUser\DTO\User;

class RandomUserApiDataProviderTest extends TestCase
{
    public function testGetOneUser(): void
    {
        $randomUserMock = $this->createUserMock(
            $firstName = 'firstName',
            $lastName = 'lastName',
            $email = 'email',
            $country = 'country',
            $userName = 'userName',
            $gender = 'gender',
            $city = 'city',
            $phone = 'phone'
        );

        $apiClientMock = $this->createMock(Client::class);
        $apiClientMock
            ->method('getRandomUser')
            ->willReturn($randomUserMock);

        $provider = new RandomUserApiUserDataProvider($apiClientMock);

        $user = $provider->getOneUser();

        $this->assertEquals($firstName, $user->getFirstName());
        $this->assertEquals($lastName, $user->getLastName());
        $this->assertEquals($email, $user->getEmail());
        $this->assertEquals($country, $user->getCountry());
        $this->assertEquals($userName, $user->getUsername());
        $this->assertEquals($gender, $user->getGender());
        $this->assertEquals($city, $user->getCity());
        $this->assertEquals($phone, $user->getPhone());
    }

    public function testGetBatch(): void
    {
        $apiClientMock = $this->createMock(Client::class);
        $apiClientMock
            ->method('getRandomUsers')
            ->willReturn(
                [
                    $this->createMock(User::class),
                    $this->createMock(User::class),
                ]
            );

        $provider = new RandomUserApiUserDataProvider($apiClientMock);

        $users = $provider->getBatch(2);

        $this->assertCount(2, $users);

        foreach ($users as $user) {
            $this->assertInstanceOf(\App\UserImporter\DTO\User::class, $user);
        }
    }

    /**
     * @return MockObject|User
     */
    private function createUserMock(
        string $firstName,
        string $lastName,
        string $email,
        string $country,
        string $userName,
        string $gender,
        string $city,
        string $phone
    ): MockObject {
        $userMock = $this->createMock(User::class);

        $userMock->method('getFirstName')->willReturn($firstName);
        $userMock->method('getLastName')->willReturn($lastName);
        $userMock->method('getEmail')->willReturn($email);
        $userMock->method('getCountry')->willReturn($country);
        $userMock->method('getUserName')->willReturn($userName);
        $userMock->method('getGender')->willReturn($gender);
        $userMock->method('getCity')->willReturn($city);
        $userMock->method('getPhone')->willReturn($phone);

        return $userMock;
    }
}