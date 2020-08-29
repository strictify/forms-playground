<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="t_user")
 */
class User
{
    use IdTrait;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private string $firstName;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private string $lastName;

    public function __construct(string $firstName, string $lastName)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function updateFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function updateLastName(string $lastName): void
    {
        $this->lastName = $lastName;
}
}
