<?php

declare(strict_types=1);

namespace App\Controller\Users\Complex\Form;

use App\Entity\User;
use App\Entity\Movie;
use App\Entity\FavoriteMovie;
use App\Struct\FavoriteMovieStruct;
use Symfony\Component\Form\AbstractType;
use App\Repository\FavoriteMovieRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * @see Movie
 * @see FavoriteMovie
 * @see FavoriteMovieStruct
 *
 * @psalm-type S=FavoriteMovie|FavoriteMovieStruct
 * @extends AbstractType<array{movie: Movie, comment: string}>
 */
class FavoriteMovieType extends AbstractType
{
    private FavoriteMovieRepository $favoriteMovieRepository;

    public function __construct(FavoriteMovieRepository $favoriteMovieRepository)
    {
        $this->favoriteMovieRepository = $favoriteMovieRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('movie', EntityType::class, [
            'class' => Movie::class,
            'placeholder' => '-- Select movie --',
            'label' => false,
            'choice_label' => fn(Movie $movie) => $movie->getName(),
            'get_value' => fn(FavoriteMovie $struct) => $struct->getMovie(),
            'update_value' => fn(Movie $movie, FavoriteMovie $struct) => $struct->setMovie($movie),
        ]);

        $builder->add('comment', TextType::class, [
            'label' => false,
            'attr' => [
                'placeholder' => '-- Comment --',
            ],
            'get_value' => fn(FavoriteMovie $struct) => $struct->getComment(),
            'update_value' => fn(string $comment, FavoriteMovie $struct) => $struct->setComment($comment),
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'label' => false,
            'factory' => fn(Movie $movie, string $comment, User $parent) => $this->favoriteMovieRepository->create($parent, $movie, $comment),
            'remove_entry' => fn(FavoriteMovie $favoriteMovie) => $this->favoriteMovieRepository->removeEntity($favoriteMovie),
        ]);
    }

    public function getBlockPrefix(): string
    {
        return 'favorite_movie';
    }
}
