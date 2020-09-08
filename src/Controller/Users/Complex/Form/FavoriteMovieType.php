<?php

declare(strict_types=1);

namespace App\Controller\Users\Complex\Form;

use App\Entity\Movie;
use App\Entity\FavoriteMovie;
use App\Struct\FavoriteMovieStruct;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * @see Movie
 * @see FavoriteMovie
 * @see FavoriteMovieStruct
 *
 * @psalm-type S=FavoriteMovie|FavoriteMovieStruct
 */
class FavoriteMovieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('movie', EntityType::class, [
            'class'        => Movie::class,
            'placeholder'  => '-- Select movie --',
            'label'        => false,
            'choice_label' => fn(Movie $movie) => $movie->getName(),
            'get_value'    => /** @psalm-param S $favoriteMovie */ fn(object $favoriteMovie) => $favoriteMovie->getMovie(),
            'update_value' => /** @psalm-param S $favoriteMovie */ fn(Movie $movie, object $favoriteMovie) => $favoriteMovie->setMovie($movie),
            'constraints'  => [
                new NotNull(['message' => 'You have to select a movie']),
            ],
        ]);

        $builder->add('comment', TextType::class, [
            'label'        => false,
            'attr'         => [
                'placeholder' => '-- Comment --',
            ],
            'get_value'    => /** @psalm-param S $favoriteMovie */ fn(object $favoriteMovie) => $favoriteMovie->getComment(),
            'update_value' => /** @psalm-param S $favoriteMovie */ fn(string $comment, object $favoriteMovie) => $favoriteMovie->setComment($comment),
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'label'   => false,
            'factory' => fn(Movie $movie, ?string $comment) => new FavoriteMovieStruct($movie, $comment),
        ]);
    }

    public function getBlockPrefix(): string
    {
        return 'favorite_movie';
    }
}
