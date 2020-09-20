<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use App\Repository\FavoriteMovieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use function array_map_i;

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

    /**
     * @psalm-var Collection<array-key, FavoriteMovie>
     * @ORM\OneToMany(targetEntity=FavoriteMovie::class, mappedBy="user")
     */
    private Collection $favoriteMovieReferences;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private int $nrOfFavoriteMovies = 0;

    /**
     * @internal
     * @psalm-internal \App\Repository\UserRepository
     */
    public function __construct(string $firstName, string $lastName)
    {
        $this->initId();
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->favoriteMovieReferences = new ArrayCollection();
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

    /**
     * @return array<Movie>
     */
    public function getFavoriteMovies(): array
    {
        $references = $this->favoriteMovieReferences->toArray();

        return array_map_i($references, fn(FavoriteMovie $reference) => $reference->getMovie());
    }

    public function getNrOfFavoriteMovies(): int
    {
        return $this->nrOfFavoriteMovies;
    }

    /**
     * @internal
     * @psalm-internal App\Repository
     *
     * @see FavoriteMovieRepository::create()
     */
    public function incNrOfFavoriteMovies(): void
    {
        $this->nrOfFavoriteMovies ++;
    }

    /**
     * @internal
     * @psalm-internal App\Repository
     *
     * @see FavoriteMovieRepository::remove()
     */
    public function decNrOfFavoriteMovies(): void
    {
        $this->nrOfFavoriteMovies --;
    }
}
