<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\UseCases\ConfirmUserUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
class ConfirmController extends AbstractController
{
    #[Route('/confirm', name: 'confirm')]
    public function confirm(Request $request, ConfirmUserUseCase $confirmUserUseCase, Security $security): Response
    {
        $error = null;
        if ($request->isMethod(Request::METHOD_POST)) {
            $user = $confirmUserUseCase->execute($request->request->getString('code'));
            if ($user instanceof User){
                $security->login($user);
                return $this->redirect('news');
            }

            $error = 'Неверный код';
        }
        return $this->render('confirm/confirm.html.twig', ['error' => $error]);
    }
}