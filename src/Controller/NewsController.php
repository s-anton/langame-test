<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
class NewsController extends AbstractController
{
    #[Route('/news', name: 'news')]
    public function index(): Response
    {
        return $this->render('news/index.html.twig');
    }
}