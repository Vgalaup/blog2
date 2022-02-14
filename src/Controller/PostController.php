<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use App\Form\CommentType;
use App\Form\PostType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    #[Route('/post/new', name: 'newPost')]
    public function newPost(Request $request, ManagerRegistry $doctrine): Response
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');


        $post = new Post;
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $post = $form->getData();
            $post->setAuthor($this->getUser());
            $post->setDate(new \DateTime());

            $image = $form['image']->getData();
            $extension = $image->guessExtension();

            if ($extension == "jpg" || $extension == "png" || $extension == "jpeg" || $extension == "svg") {
                $filename = $image->getClientOriginalName() . rand(1, 99999) . '.' . $extension;
                $image->move("images", $filename);
            }
            $post->setImage($filename);
            $entityManager = $doctrine->getManager();

            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('newPost');
        }




        return $this->renderForm('post/new.html.twig', [
            'controller_name' => 'PostController',
            'form' => $form
        ]);
    }



    #[Route('/post/{id}', name: 'onePost')]
    public function onePost(Request $request, ManagerRegistry $doctrine, string $id): Response
    {
        $post = $doctrine->getRepository(Post::class)->find($id);
        $comments = $post->getComments();

        $comment = new Comment;

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);




        return $this->renderForm('post/onePost.html.twig', [
            'post' => $post,
            'form' => $form,
            'comments' => $comments,
        ]);
    }

    #[Route('/post/author/{id}', name: 'postFromUser')]
    public function postFromUser(ManagerRegistry $doctrine, string $id): Response
    {
        $user = $doctrine->getRepository(User::class)->find($id);
        $posts = $user->getPosts();


        return $this->render('post/postFromUser.html.twig', [
            'posts' => $posts,
            'author' => $user,
        ]);
    }

    #[Route('post/displayPosts/{counter}', name: 'displayPosts')]
    public function displayPosts(ManagerRegistry $doctrine,  $counter): Response
    {

        $posts = $doctrine->getRepository(Post::class)->findAll();
        $posts = array_slice($posts, $counter, 3);
        $categories = $doctrine->getRepository(Category::class)->findAll();

        return $this->render('home/displayPosts.html.twig', [
            'controller_name' => 'PostController',
            'posts' => $posts,
            'categories' => $categories
        ]);
    }



    #[Route('/post/date/{date}', name: 'postByDay')]
    public function postByDay(ManagerRegistry $doctrine, $date): Response
    {
        $posts = $doctrine->getRepository(Post::class)->findAllByDay($date);


        return $this->render('post/postByDay.html.twig', [
            'posts' => $posts,
        ]);
    }


    #[Route('/myPosts', name: 'myPosts')]
    public function index(Request $request, ManagerRegistry $doctrine): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();

        return $this->renderForm('post/myposts.html.twig', [
            'controller_name' => 'ProfileController',
            'user' => $user,
        ]);
    }
}
