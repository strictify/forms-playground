<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\MovieRepository;

/**
 * @ORM\Entity(repositoryClass=MovieRepository::class)
 * @ORM\Table(name="t_movie")
 */
class Movie
{
    use IdTrait;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private string $name;

    public function __construct(string $name)
    {
        $this->initId();
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function rename(string $name): void
    {
        $this->name = $name;
    }
}
