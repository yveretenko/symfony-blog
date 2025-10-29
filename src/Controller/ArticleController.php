<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleCreateFormType;
use App\Service\ArticleService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/article', name: 'article_')]
class ArticleController extends AbstractController
{
    public function __construct(private readonly ArticleService $articleService) {}

    #[Route('/create', name: 'create')]
    public function create(Request $request): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleCreateFormType::class, $article);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->render('article/create.html.twig', [
                'form' => $form->createView(),
            ]);
        }

        $article = $this->articleService->createAndFlush(
            $article->getTitle(),
            $article->getDescription()
        );

        return $this->redirectToRoute('article_congratulation', [
            'id' => $article->getId(),
        ]);
    }

    #[Route('/create/congratulation/{id}', name: 'congratulation')]
    public function congratulation(int $id): Response
    {
        return $this->render('article/congratulation.html.twig', [
            'id' => $id,
        ]);
    }
}
