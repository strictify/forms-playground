<?php

declare(strict_types=1);

namespace App\Controller\Users\Complex\Form;

use App\Entity\Movie;
use App\Entity\FavoriteMovie;
use App\Struct\FavoriteMovieStruct;
use Doctrine\Instantiator\Instantiator;
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
            'get_value'    => fn(FavoriteMovie $struct) => $struct->getMovie(),
            'update_value' => fn(Movie $movie, FavoriteMovie $struct) => $struct->setMovie($movie),
            'constraints'  => [
                new NotNull(),
            ],
        ]);

        $builder->add('comment', TextType::class, [
            'label'     => false,
            'attr'      => [
                'placeholder' => '-- Comment --',
            ],
            'get_value' => fn(FavoriteMovie $struct) => $struct->getComment(),
            'update_value' => fn(string $comment, FavoriteMovie $struct) => $struct->setComment($comment),
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'label'   => false,
            'factory' => function (Movie $movie, string $comment) {
                /** @var FavoriteMovie $struct */
                $struct = (new Instantiator())->instantiate(FavoriteMovie::class);
                $struct->setMovie($movie);
                $struct->setComment($comment);

                return $struct;
            },
        ]);
    }

    public function getBlockPrefix(): string
    {
        return 'favorite_movie';
    }
}
