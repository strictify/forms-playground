<?php

declare(strict_types=1);

namespace App\Controller\Users;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ListAction extends AbstractController
{
    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/", name="user_list")
     */
    public function __invoke(): Response
    {
        $users = $this->repository->findAll();

        return $this->render('users/list.html.twig', [
            'users' => $users,
        ]);
    }
}
