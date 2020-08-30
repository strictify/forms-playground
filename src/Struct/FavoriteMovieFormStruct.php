<?php

declare(strict_types=1);

namespace App\Struct;

use App\Entity\User;
use App\Entity\Movie;

class FavoriteMovieFormStruct
{
    public bool $isFavorite = false;
    public ?string $comment = null;

    public User $user;

    public Movie $movie;

    public function __construct(User $user, Movie $movie)
    {
        $this->user = $user;
        $this->movie = $movie;
    }
}
