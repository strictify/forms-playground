<?php

declare(strict_types=1);

namespace App\Controller\Movies;

use App\Repository\MovieRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/", name="movie_list", methods={"GET"})
 */
class ListAction extends AbstractController
{
    private MovieRepository $repository;

    public function __construct(MovieRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(): Response
    {
        $users = $this->repository->findAll();

        return $this->render('movies/list.html.twig', [
            'movies' => $users,
        ]);
    }
}
