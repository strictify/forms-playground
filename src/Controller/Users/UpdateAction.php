<?php

declare(strict_types=1);

namespace App\Controller\Users;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UpdateAction extends AbstractController
{
    /**
     * @Route("/update/{id}", name="user_update", methods={"GET", "POST"})
     */
    public function __invoke(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() && $form->getData()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('user_list');
        }

        return $this->render('users/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
