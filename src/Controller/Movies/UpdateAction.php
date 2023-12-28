<?php

declare(strict_types=1);

namespace App\Controller\Movies;

use App\Entity\Movie;
use App\Form\MovieType;
use App\Annotation\Form;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Form(class=MovieType::class, data="movie")
 */
class UpdateAction extends AbstractController
{
    /**
     * @Route("/update/{id}", name="movie_update", methods={"GET", "POST"})
     *
     * @param FormInterface<Movie> $form
     */
    public function __invoke(FormInterface $form, Movie $movie, EntityManagerInterface $em): Response
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('movie_list');
        }

        return $this->render('forms/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
