<?php

declare(strict_types=1);

namespace App\Controller\Users\Complex\Form;

use App\Entity\User;
use App\Entity\Movie;
use App\Form\BasicUserType;
use App\Entity\FavoriteMovie;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use App\Repository\FavoriteMovieRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Event\PreSetDataEvent;
use function array_map;

/**
 * @extends AbstractType<User>
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
        $repo = $this->repository;

        $builder->add('favoriteMovies', EntityType::class, [
            'class'        => Movie::class,
            'choice_label' => fn(Movie $movie) => $movie->getName(),
            'multiple'     => true,
            'get_value'    => function (User $user) {
                $repo = $this->repository;
                $refs = $repo->getResults($repo->whereUser($user));

                return array_map(fn(FavoriteMovie $ref) => $ref->getMovie(), $refs);
            },
            'add_value'    => fn(Movie $movie, User $data) => $this->repository->create($data, $movie),
            'remove_value' => fn(Movie $movie, User $data) => $this->repository->remove($data, $movie),
        ]);

        /** @psalm-suppress UnusedVariable */
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (PreSetDataEvent $event) {
            $user = $event->getData();
            /** @psalm-trace $user */
        });
    }

    public function getParent(): string
    {
        return BasicUserType::class;
    }
}
