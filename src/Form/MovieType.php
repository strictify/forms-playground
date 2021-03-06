<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * @extends AbstractType<Movie>
 */
class MovieType extends AbstractType
{
    private MovieRepository $movieRepository;

    public function __construct(MovieRepository $movieRepository)
    {
        $this->movieRepository = $movieRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name', TextType::class, [
            'get_value'    => fn(Movie $movie) => $movie->getName(),
            'update_value' => fn(string $name, Movie $movie) => $movie->rename($name),
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'factory' => fn(string $name) => $this->movieRepository->create($name),
        ]);
    }
}
