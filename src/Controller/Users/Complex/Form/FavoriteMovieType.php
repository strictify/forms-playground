<?php

declare(strict_types=1);

namespace App\Controller\Users\Complex\Form;

use App\Entity\FavoriteMovie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class FavoriteMovieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('isFavorite', CheckboxType::class, [
            'get_value' => fn(FavoriteMovie $favoriteMovie) => true,
            'update_value' => function (bool $isFavorite, FavoriteMovie $favoriteMovie) {
                return null;
            },
        ]);

        $builder->add('comment', TextType::class, [
            'get_value' => fn(FavoriteMovie $struct) => $struct->getComment(),
            'update_value' => fn(string $comment, FavoriteMovie $favoriteMovie) => $favoriteMovie->setComment($comment),
        ]);
    }
}
