<?php

declare(strict_types=1);

namespace App\Controller\Users\Simple;

use App\Entity\User;
use App\Form\UserType;
use App\Annotation\Form;
use App\Repository\UserRepository;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Form(class=UserType::class)
 */
class CreateAction extends AbstractController
{
    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/create", name="user_create_simple", methods={"GET", "POST"})
     *
     * @param FormInterface<User> $form
     */
    public function __invoke(FormInterface $form): Response
    {
        if ($form->isSubmitted() && $form->isValid() && $user = $form->getData()) {
            $this->repository->save($user);

            return $this->redirectToRoute('user_list');
        }

        return $this->render('forms/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
