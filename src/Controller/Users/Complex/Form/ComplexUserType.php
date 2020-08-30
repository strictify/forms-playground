<?php

declare(strict_types=1);

namespace App\Controller\Users\Complex\Form;

use App\Entity\User;
use App\Entity\Movie;
use App\Form\BasicUserType;
use App\Entity\FavoriteMovie;
use App\Repository\MovieRepository;
use Symfony\Component\Form\AbstractType;
use App\Repository\FavoriteMovieRepository;
use Symfony\Component\Form\FormBuilderInterface;
use function dump;
use function func_get_args;

/**
 * @extends AbstractType<User>
 *
 * @see User
 */
class ComplexUserType extends AbstractType
{
    private FavoriteMovieRepository $favoriteMovieRepository;
    private MovieRepository $movieRepository;

    public function __construct(FavoriteMovieRepository $favoriteMovieRepository, MovieRepository $movieRepository)
    {
        $this->favoriteMovieRepository = $favoriteMovieRepository;
        $this->movieRepository = $movieRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $allMovies = $this->movieRepository->findAll();
        foreach ($allMovies as $key => $movie) {
            $builder->add('movie_' . $key, FavoriteMovieType::class, [
                'label' => $movie->getName(),
                'get_value'    => fn(User $user) => $this->createReferences($user, $movie),
                'update_value' => function ($data, User $user) {
                    dump(func_get_args());
                    return null;
                },
            ]);
        }
    }

    private function createReferences(User $user, Movie $movie)
    {
        $repo = $this->favoriteMovieRepository;
        $reference = $repo->matchOneBy($repo->whereUser($user), $repo->whereMovie($movie));
        if ($reference) {
            return $reference;
        }

        return new FavoriteMovie($user, $movie);
        dump($movie);
        return $repo->create($user, $movie);
    }

    public function getParent(): string
    {
        return BasicUserType::class;
    }
}
