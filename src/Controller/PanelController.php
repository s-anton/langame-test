<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\ConfirmationCodeRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
class PanelController extends AbstractController
{
    #[Route('/panel', name: 'panel')]
    public function panel(
        UserRepository $userRepository,
        ConfirmationCodeRepository $confirmationCodeRepository
    ): Response {
        $users = $userRepository->findAll();
        $confirmationCodes = $confirmationCodeRepository->getAll();
        $codes = [];
        foreach ($confirmationCodes as $code) {
            $codes[$code->getUserId()] = $code->getCode();
        }

        return $this->render('panel/index.html.twig', compact('users', 'codes'));
    }
}