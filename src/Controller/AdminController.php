<?php

namespace App\Controller;

use App\Entity\Post;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'adminPosts')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $posts = $doctrine->getRepository(Post::class)->findBy([], ['id' => 'DESC']);


        return $this->render('admin/adminPosts.html.twig', [
            'posts' => $posts,
        ]);
    }

    #[Route('/admin/deletePost/{id}', name: 'adminDeletePost')]
    public function delete(string $id, Request $request, ManagerRegistry $doctrine)
    {

        $entityManager = $doctrine->getManager();


        $post = $doctrine->getRepository(Post::class)->find($id);
        $entityManager->remove($post);
        $entityManager->flush();

        return $this->redirectToRoute('adminPosts');
    }
}
