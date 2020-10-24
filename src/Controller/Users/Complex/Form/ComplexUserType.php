<?php

declare(strict_types=1);

namespace App\Controller\Users\Complex\Form;

use App\Entity\User;
use App\Form\BasicUserType;
use App\Entity\FavoriteMovie;
use App\Struct\FavoriteMovieStruct;
use Symfony\Component\Form\AbstractType;
use App\Repository\FavoriteMovieRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use function array_map_i;
use function array_unique;
use function count;
use function array_filter;

/**
 * @extends AbstractType<User>
 *
 * @see User
 * @see FavoriteMovie
 * @see FavoriteMovieStruct
 *
 * @psalm-type S=FavoriteMovie|FavoriteMovieStruct
 */
class ComplexUserType extends AbstractType
{
    private FavoriteMovieRepository $favoriteMovieRepository;

    public function __construct(FavoriteMovieRepository $favoriteMovieRepository)
    {
        $this->favoriteMovieRepository = $favoriteMovieRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $repo = $this->favoriteMovieRepository;

        $builder->add('movies', CollectionType::class, [
            'entry_type'   => FavoriteMovieType::class,
            'allow_add'    => true,
//            'delete_empty' => true,
            'allow_delete' => true,
            'add_value' => fn(FavoriteMovie $struct, User $data) => $repo->create($data, $struct->getMovie(), $struct->getComment()),
            'get_value'    => fn(User $user) => $repo->getResults($repo->whereUser($user)),
            'remove_value' => fn(FavoriteMovie $favoriteMovie) => $repo->removeEntity($favoriteMovie),
            'constraints'  => [
                new Count(['min' => 1]),
                new Callback(['callback' => [$this, 'assertUniqueMovies']]),
            ],
        ]);
    }

    /**
     * Each movie can be added only once.
     *
     * @psalm-param array<FavoriteMovie|null> $movies
     */
    public function assertUniqueMovies(array $movies, ExecutionContextInterface $executionContext): void
    {
        $movies = array_filter($movies, fn($movie) => $movie !== null);
        $ids = array_map_i($movies, /** @psalm-param S $ref */ fn($ref) => (string)$ref->getMovie()->getId());
        if (count($ids) !== count(array_unique($ids))) {
            $executionContext->addViolation('You have duplicate movies.');
        }
    }

    public function getParent(): string
    {
        return BasicUserType::class;
    }
}
