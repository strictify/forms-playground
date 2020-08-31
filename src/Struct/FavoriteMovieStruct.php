<?php

declare(strict_types=1);

namespace App\Struct;

use App\Entity\Movie;

class FavoriteMovieStruct
{
    private ?string $comment;

    private Movie $movie;

    public function __construct(Movie $movie, ?string $comment)
    {
        $this->movie = $movie;
        $this->comment = $comment;
    }

    public function getMovie(): Movie
    {
        return $this->movie;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): void
    {
        $this->comment = $comment;
    }

    public function setMovie(Movie $movie): void
    {
        $this->movie = $movie;
    }
}
