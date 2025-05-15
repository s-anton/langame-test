<?php

declare(strict_types=1);

namespace App\Controller;

use App\UseCases\ConfirmUserUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
class ConfirmController extends AbstractController
{
    #[Route('/confirm', name: 'confirm')]
    public function confirm(Request $request, ConfirmUserUseCase $confirmUserUseCase): Response
    {
        $error = null;
        if ($request->isMethod(Request::METHOD_POST)) {
            if ($confirmUserUseCase->execute($request->request->getString('code'))){
                return $this->redirect('news');
            }

            $error = 'Неверный код';
        }
        return $this->render('confirm/confirm.html.twig', ['error' => $error]);
    }
}