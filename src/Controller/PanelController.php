<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Twig\Environment;

#[AsController]
#[Route('/panel', name: 'panel')]
class PanelController
{
    public function __invoke(Environment $twig): Response
    {
        return new Response($twig->render('base.html.twig'));
    }

}