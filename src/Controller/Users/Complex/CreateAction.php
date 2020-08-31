<?php

declare(strict_types=1);

namespace App\Controller\Users\Complex;

use App\Entity\User;
use App\Annotation\Form;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\Users\Complex\Form\ComplexUserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Form(class=ComplexUserType::class)
 */
class CreateAction extends AbstractController
{
    /**
     * @Route("/create", name="user_create_complex", methods={"GET", "POST"})
     *
     * @param FormInterface<User> $form
     */
    public function __invoke(FormInterface $form): Response
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_list');
        }

        return $this->render('forms/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
