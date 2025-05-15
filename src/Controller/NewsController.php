<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\NewsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/news/list', name: 'news_list')]
    public function list(Request $request, NewsRepository $newsRepository): Response
    {
        $lastPublishedAt = $request->query->getString(
            'lastPublishedAt',
            date_create('+1 hour')->format('Y-m-d H:i:s')
        );
        $query = $request->query->getString('query');
        $direction = $request->query->getString('direction', 'forward');

        return $this->render('news/list.html.twig', [
            'news' => $newsRepository->iterateUsingCursor($lastPublishedAt, $query, $direction),
            'minMaxPublishedAt' => $newsRepository->getMinMaxPublishedAt(),
        ]);
    }
}