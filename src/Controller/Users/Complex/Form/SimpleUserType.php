<?php

declare(strict_types=1);

namespace App\Controller\Users\Complex\Form;

use App\Entity\User;
use App\Entity\Movie;
use App\Form\BasicUserType;
use Symfony\Component\Form\AbstractType;
use App\Repository\FavoriteMovieRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

/**
 * @extends AbstractType<User>
 *
 * @see User
 */
class SimpleUserType extends AbstractType
{
    private FavoriteMovieRepository $repository;

    public function __construct(FavoriteMovieRepository $favoriteMovieRepository)
    {
        $this->repository = $favoriteMovieRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('favoriteMovies', EntityType::class, [
            'class'        => Movie::class,
            'choice_label' => fn(Movie $movie) => $movie->getName(),
            'multiple'     => true,
            'get_value'    => fn(User $user) => $user->getFavoriteMovies(),
            'add_value'    => fn(Movie $movie, User $user) => $this->repository->create($user, $movie),
            'remove_value' => fn(Movie $movie, User $user) => $this->repository->remove($user, $movie),
        ]);
    }

    public function getParent(): string
    {
        return BasicUserType::class;
    }

}
