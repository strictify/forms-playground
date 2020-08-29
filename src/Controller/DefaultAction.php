<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultAction extends AbstractController
{
    /**
     * @Route("/", name="default")
     */
    public function __invoke(): Response
    {
        return $this->render('default/index.html.twig');
    }
}
