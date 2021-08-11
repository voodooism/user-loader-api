<?php
declare(strict_types=1);

namespace App\UserImporter\DTO;

/**
 * @psalm-immutable
 */
class User
{
    private string $firstName;

    private string $lastName;

    private string $email;

    private string $country;

    private string $username;

    private string $gender;

    private string $city;

    private string $phone;

    public function __construct(
        string $firstName,
        string $lastName,
        string $email,
        string $country,
        string $username,
        string $gender,
        string $city,
        string $phone
    ) {
        $this->firstName = $firstName;
        $this->lastName  = $lastName;
        $this->email     = $email;
        $this->country   = $country;
        $this->username  = $username;
        $this->gender    = $gender;
        $this->city      = $city;
        $this->phone     = $phone;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getFullName(): string
    {
        return sprintf('%s %s', $this->firstName, $this->lastName);
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getGender(): string
    {
        return $this->gender;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }
}
