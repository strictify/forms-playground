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
 * @Route("/create", name="user_create", methods={"GET", "POST"})
 *
 * @Form(class=UserType::class)
 */
class CreateAction extends AbstractController
{
    /**
     * @param FormInterface<User> $form
     */
    public function __invoke(FormInterface $form): Response
    {
        if ($form->isSubmitted() && $form->isValid() && $user = $form->getData()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('user_list');
        }

        return $this->render('forms/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
