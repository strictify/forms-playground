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
            'allow_delete' => true,
            'get_value'    => fn(User $user) => $repo->getResults($repo->whereUser($user)),
            'add_value'    => fn(FavoriteMovieStruct $struct, User $user) => $repo->create($user, $struct->getMovie(), $struct->getComment()),
            'remove_value' => fn(FavoriteMovie $data) => $repo->removeEntity($data),
            'constraints'  => [
                new Count(['min' => 1]),
                new Callback(['callback' => [$this, 'assertUniqueMovies']]),
            ],
        ]);
    }

    /**
     * Each movie can be added only once.
     *
     * @psalm-param array<S|null> $movies
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
