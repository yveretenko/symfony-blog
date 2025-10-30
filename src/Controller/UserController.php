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
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/user', name: 'user_')]
class UserController extends AbstractController
{
    public function __construct(
        private readonly ValidatorInterface $validator,
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

        $user = (new User())
            ->setUsername($formData['username'] ?? '')
            ->setFirstName($formData['firstName'] ?? '')
            ->setLastName($formData['lastName'] ?? '');

        $errors = $this->validator->validate($user);

        if (count($errors) > 0) {
            return $this->render('user/create.html.twig', [
                'form'   => $form->createView(),
                'errors' => $errors,
            ]);
        }

        $user = $this->userService->createAndFlush(
            $user->getUsername(),
            $user->getFirstName(),
            $user->getLastName(),
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
