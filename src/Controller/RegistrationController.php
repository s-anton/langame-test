<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationForm;
use App\UseCases\Auth\RegisterUserUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, RegisterUserUseCase $registerUserUseCase): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationForm::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();
            /** @var string $username */
            $username = $form->get('username')->getData();

            $registerUserUseCase->execute($username, $plainPassword);

            return $this->redirectToRoute('confirm');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
}
