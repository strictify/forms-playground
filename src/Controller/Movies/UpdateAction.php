<?php

declare(strict_types=1);

namespace App\Controller\Movies;

use App\Entity\Movie;
use App\Form\MovieType;
use App\Annotation\Form;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/update/{id}", name="movie_update", methods={"GET", "POST"})
 *
 * @Form(class=MovieType::class, data="movie")
 */
class UpdateAction extends AbstractController
{
    /**
     * @param FormInterface<Movie> $form
     */
    public function __invoke(FormInterface $form, Movie $movie): Response
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('movie_list');
        }

        return $this->render('forms/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
