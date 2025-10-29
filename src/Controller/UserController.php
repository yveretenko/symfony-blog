<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\UserCreateFormType;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/user', name: 'user_')]
class UserController extends AbstractController
{
    public function __construct(private readonly UserService $userService) {}

    #[Route('/create', name: 'create')]
    public function create(Request $request): Response
    {
        $user = new User();

        $form = $this->createForm(UserCreateFormType::class, $user);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->render('user/create.html.twig', [
                'form' => $form->createView(),
            ]);
        }

        $user = $this->userService->createAndFlush(
            $user->getUsername(),
            $user->getFirstName(),
            $user->getLastName()
        );

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
