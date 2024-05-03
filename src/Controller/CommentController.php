<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentController extends AbstractController
{
    #[Route('/post-comment/{articleId}', name: 'comment_post', methods: ['POST'])]
    public function index(Request $request, $articleId, ArticleRepository $articleRepository, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $article = $articleRepository->findOneById($articleId);

        if (!$article) {
            throw $this->createNotFoundException('No article found for id ' . $articleId);
        }

        $comment = new Comment();
        $comment->setUser($user);
        $comment->setContent($request->request->get('comment'));
        $comment->setArticle($article);
        $comment->setPublicationDate(new \DateTime());
        $comment->setModificationDate(new \DateTime());

        $entityManager->persist($comment);
        $entityManager->flush();


        return $this->redirectToRoute('show_one_article', ['id' => $articleId]);
    }
}
