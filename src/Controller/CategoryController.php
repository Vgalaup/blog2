<?php

namespace App\Controller;

use App\Entity\Category;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class CategoryController extends AbstractController
{
    #[Route('/category/{id}', name: 'Category')]
    public function postFromCategory(ManagerRegistry $doctrine, string $id): Response
    {
        $category = $doctrine->getRepository(Category::class)->find($id);
        $posts = $category->getPosts();

        return $this->render('category/category.html.twig', [
            'posts' => $posts,
            'category' => $category,
        ]);
    }




    #[Route('/category', name: 'categories')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $categories = $doctrine->getRepository(Category::class)->findAll();


        return $this->render('category/categories.html.twig', [
            'controller_name' => 'CategoryController',
            'categories' => $categories
        ]);
    }
}
