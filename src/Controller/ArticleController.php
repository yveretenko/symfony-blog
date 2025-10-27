<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\ArticleType;
use App\Service\ArticleService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ArticleController extends AbstractController
{
    public function __construct(private readonly ArticleService $articleService) {}

    #[Route('/article/create', name: 'article_create')]
    public function create(Request $request): Response
    {
        $form = $this->createForm(ArticleType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $article = $this->articleService->createAndFlush(
                $data['title'],
                $data['description']
            );

            return $this->redirectToRoute('article_congratulation', [
                'id' => $article->getId(),
            ]);
        }

        return $this->render('article/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/article/create/congratulation/{id}', name: 'article_congratulation')]
    public function congratulation(int $id): Response
    {
        return $this->render('article/congratulation.html.twig', [
            'id' => $id,
        ]);
    }
}
