<?php

declare(strict_types=1);

namespace App\Controller\Users;

use App\Entity\User;
use App\Form\UserType;
use App\Annotation\Form;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Form(class=UserType::class, data="user")
 */
class UpdateAction extends AbstractController
{
    /**
     * @Route("/update/{id}", name="user_update", methods={"GET", "POST"})
     *
     * @param FormInterface<User> $form
     */
    public function __invoke(FormInterface $form, User $user): Response
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('user_list');
        }

        return $this->render('users/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
