<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\User;
use App\Entity\Movie;
use App\Entity\FavoriteMovie;
use Symfony\Component\Form\AbstractType;
use App\Repository\FavoriteMovieRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use function array_map_i;

/**
 * @extends AbstractType<User>
 */
class UserType extends AbstractType
{
    private FavoriteMovieRepository $repository;

    public function __construct(FavoriteMovieRepository $favoriteMovieRepository)
    {
        $this->repository = $favoriteMovieRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('firstName', TextType::class, [
            'get_value'    => fn(User $user) => $user->getFirstName(),
            'update_value' => fn(string $firstName, User $user) => $user->updateFirstName($firstName),
        ]);

        $builder->add('lastName', TextType::class, [
            'get_value'    => fn(User $user) => $user->getLastName(),
            'update_value' => fn(string $lastName, User $user) => $user->updateLastName($lastName),
        ]);

        $builder->add('favoriteMovies', EntityType::class, [
            'class'        => Movie::class,
            'choice_label' => fn(Movie $movie) => $movie->getName(),
            'multiple'     => true,
            'get_value'    => function (User $user) {
                $favMovies = $this->repository->matchBy($this->repository->whereUser($user));

                return array_map_i($favMovies, fn(FavoriteMovie $favoriteMovie) => $favoriteMovie->getMovie());
            },
            'add_value'    => fn(Movie $movie, User $user) => $this->repository->createFor($user, $movie),
            'remove_value' => fn(Movie $movie, User $user) => $this->repository->removeFor($user, $movie),
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'factory' => fn(string $firstName, string $lastName) => new User($firstName, $lastName),
        ]);
    }
}
