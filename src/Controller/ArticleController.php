<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Article;
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
        $article = new Article();

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article = $this->articleService->createAndFlush(
                $article->getTitle(),
                $article->getDescription()
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
