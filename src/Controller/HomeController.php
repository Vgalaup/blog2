<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Category;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    // #[Route('/searchingElement/{id}', name: 'searchElement')]
    // public function AddElementToSearch(SessionInterface $session, ManagerRegistry $doctrine, int $id): Response
    // {
    //     $session = [];
    //     $element = $doctrine->getRepository(Category::class)->find($id);
    //     if (in_array($element, $session)) {
    //     } else {
    //         $session[] = $element;
    //     }


    //     return $this->render('home/index.html.twig', [
    //     ]);
    // }

    #[Route('/', name: 'home')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $posts = $doctrine->getRepository(Post::class)->findBy([], ['id' => 'DESC']);
        $categories = $doctrine->getRepository(Category::class)->findAll();

        return $this->render('home/index.html.twig', [
            'posts' => $posts,
            'categories' => $categories
        ]);
    }
}
