<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exception\ValidationException;
use App\Form\UserCreateFormType;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/user', name: 'user_')]
class UserController extends AbstractController
{
    public function __construct(
        private readonly UserService $userService,
    ) {}

    #[Route('/create', name: 'create')]
    public function create(Request $request): Response
    {
        $form = $this->createForm(UserCreateFormType::class);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->render('user/create.html.twig', [
                'form' => $form->createView(),
            ]);
        }

        $formData = $form->getData();

        try {
            $user = $this->userService->validateAndFlush(
                $formData['username'] ?? '',
                $formData['firstName'] ?? '',
                $formData['lastName'] ?? '',
                // TODO: after authentication is implemented, set password here
            );
        } catch (ValidationException $e) {
            return $this->render('user/create.html.twig', [
                'form'   => $form->createView(),
                'errors' => $e->getErrors(),
            ]);
        }

        return $this->redirectToRoute('user_congratulation', [
            'id' => $user->getId(),
        ]);
    }

    #[Route('/create/congratulation/{id}', name: 'congratulation')]
    public function congratulation(int $id): Response
    {
        return $this->render('user/congratulation.html.twig', [
            'id' => $id,
        ]);
    }
}
