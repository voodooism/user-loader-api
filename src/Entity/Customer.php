<?php

namespace App\Entity;

use App\Repository\CustomerRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use RuntimeException;

/**
 * @ORM\Entity(repositoryClass=CustomerRepository::class)
 */
class Customer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"api:customer:list"})
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({"api:customer:list"})
     */
    private string $fullName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({"api:customer:list"})
     */
    private string $email;

    /**
     * @ORM\Column(type="string", length=64)
     * @Serializer\Groups({"api:customer:list"})
     */
    private string $country;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $username;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private string $gender;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private string $city;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private string $phone;

    public function __construct(
        string $fullName,
        string $email,
        string $country,
        string $username,
        string $gender,
        string $city,
        string $phone
    ) {
        $this->fullName  = $fullName;
        $this->email     = $email;
        $this->country   = $country;
        $this->username  = $username;
        $this->gender    = $gender;
        $this->city      = $city;
        $this->phone     = $phone;
    }

    public function getId(): int
    {
        if ($this->id === null) {
            throw new RuntimeException('Id is null');
        }

        return $this->id;
    }

    public function getFullName(): string
    {
        return $this->fullName;
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

    public function setFullName(string $fullName): void
    {
        $this->fullName = $fullName;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setCountry(string $country): void
    {
        $this->country = $country;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function setGender(string $gender): void
    {
        $this->gender = $gender;
    }

    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }
}
