<?php

namespace App\Entity;

use DateTime;
use App\Repository\FavoriteMovieRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FavoriteMovieRepository::class)
 * @ORM\Table(name="t_user_favorite_movie")
 */
class FavoriteMovie
{
    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private User $user;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity=Movie::class)
     */
    private Movie $movie;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private DateTime $createdAt;

    public function __construct(User $user, Movie $movie)
    {
        $this->user = $user;
        $this->movie = $movie;
        $this->createdAt = new DateTime();
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }
}
