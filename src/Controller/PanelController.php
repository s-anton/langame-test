<?php

declare(strict_types=1);

namespace App\Controller;

use App\Events\Sync\UserVerified;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Twig\Environment;

#[AsController]
#[Route('/panel', name: 'panel')]
class PanelController
{
    public function __invoke(Environment $twig, MessageBusInterface $messageBus): Response
    {
        $messageBus->dispatch(new UserVerified(1));
        return new Response($twig->render('base.html.twig'));
    }

}