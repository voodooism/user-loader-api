<?php

namespace App\UserImporter;

interface UserImporterInterface
{
    public function importUser(): void;

    public function importBatch(int $count): void;
}
