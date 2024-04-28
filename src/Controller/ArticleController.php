<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ArticleController extends AbstractController
{
    #[Route('/article/{id}', name: 'article_show')]
    public function detail($id, ArticleRepository $articleRepository, CommentRepository $commentRepository): Response
    {
        $article = $articleRepository->findOneById($id);
        $comments = $commentRepository->findLatestByArticle($article->getId());
        $previousArticle = $articleRepository->findPreviousArticle($article);
        $nextArticle = $articleRepository->findNextArticle($article);

        // Vérifier si l'article existe
        if (!$article) {
            throw $this->createNotFoundException('L\'article avec l\'ID ' . $id . ' n\'existe pas.');
        }

        // Passer les détails de l'article au template Twig pour l'affichage
        return $this->render('article/index.html.twig', [
            'article' => $article,
            "comments" => $comments,
            'previousArticle' => $previousArticle,
            'nextArticle' => $nextArticle,
        ]);
    }
}
