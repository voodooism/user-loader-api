<?php
declare(strict_types=1);

namespace App\UserImporter\DataProvider;

use App\UserImporter\DTO\User;
use App\UserImporter\DTO\UserCollection;

interface UserDataProviderInterface
{
    public function getOneUser(): User;

    public function getBatch(int $count): UserCollection;
}
