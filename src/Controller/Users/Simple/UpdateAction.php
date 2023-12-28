<?php

declare(strict_types=1);

namespace App\Controller\Users\Simple;

use App\Entity\User;
use App\Annotation\Form;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\Users\Complex\Form\SimpleUserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Form(class=SimpleUserType::class, data="user")
 */
class UpdateAction extends AbstractController
{
    /**
     * @Route("/update/{id}", name="user_update_simple", methods={"GET", "POST"})
     *
     * @param FormInterface<User> $form
     */
    public function __invoke(FormInterface $form, User $user, EntityManagerInterface $em): Response
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('user_list');
        }

        return $this->render('forms/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
