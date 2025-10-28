<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    public function __construct(private readonly UserService $userService) {}

    #[Route('/user/create', name: 'user_create')]
    public function create(Request $request): Response
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->userService->createAndFlush(
                $user->getUsername(),
                $user->getFirstName(),
                $user->getLastName()
            );

            return $this->redirectToRoute('user_congratulation', [
                'id' => $user->getId(),
            ]);
        }

        return $this->render('user/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/user/create/congratulation/{id}', name: 'user_congratulation')]
    public function congratulation(int $id): Response
    {
        return $this->render('user/congratulation.html.twig', [
            'id' => $id,
        ]);
    }
}
