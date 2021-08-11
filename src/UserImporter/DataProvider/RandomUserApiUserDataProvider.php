<?php
declare(strict_types=1);

namespace App\UserImporter\DataProvider;

use App\UserImporter\DTO\User;
use App\UserImporter\DTO\UserCollection;
use Voodooism\RandomUser\Client;
use Voodooism\RandomUser\Enum\NationalityEnum;
use Voodooism\RandomUser\Request\RequestOptions;

class RandomUserApiUserDataProvider implements UserDataProviderInterface
{
    private Client $randomUserApiClient;

    public function __construct(Client $randomUserApiClient)
    {
        $this->randomUserApiClient = $randomUserApiClient;
    }

    public function getOneUser(): User
    {
        $options = new RequestOptions();
        $options->setNat(NationalityEnum::AU);

        $randomUser = $this->randomUserApiClient->getRandomUser($options);

        $user = new User(
            $randomUser->getFirstName(),
            $randomUser->getLastName(),
            $randomUser->getEmail(),
            $randomUser->getCountry(),
            $randomUser->getUserName(),
            $randomUser->getGender(),
            $randomUser->getCity(),
            $randomUser->getPhone()
        );

        return $user;
    }

    public function getBatch(int $count): UserCollection
    {
        $options = new RequestOptions();
        $options->setNat(NationalityEnum::AU);

        $randomUsers = $this->randomUserApiClient->getRandomUsers($count, $options);

        $result = [];

        foreach ($randomUsers as $randomUser) {
            $result[] = new User(
                $randomUser->getFirstName(),
                $randomUser->getLastName(),
                $randomUser->getEmail(),
                $randomUser->getCountry(),
                $randomUser->getUserName(),
                $randomUser->getGender(),
                $randomUser->getCity(),
                $randomUser->getPhone()
            );
        }

        return new UserCollection($result);
    }
}
