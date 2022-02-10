<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{

    #[Route('/comment/{content}/{postId}', name: 'comment')]
    public function index(ManagerRegistry $doctrine, string $content, string $postId): Response
    {
        $post = $doctrine->getRepository(Post::class)->find($postId);

        $comment = new Comment;

        $comment->setContent($content);
        $comment->setAuthor($this->getUser());
        $comment->setPost($post);
        $comment->setDate(new \DateTime());

        $entityManager = $doctrine->getManager();
        $entityManager->persist($comment);
        $entityManager->flush();

        return $this->render('comment/index.html.twig', [
            'controller_name' => 'CommentController',
            'comment' => $comment
        ]);
    }
}
