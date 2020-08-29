<?php

declare(strict_types=1);

namespace App\Controller\Users;

use App\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CreateAction extends AbstractController
{
    /**
     * @Route("/create", name="user_create", methods={"GET", "POST"})
     */
    public function __invoke(Request $request): Response
    {
        $form = $this->createForm(UserType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() && $user = $form->getData()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('user_list');
        }

        return $this->render('users/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
