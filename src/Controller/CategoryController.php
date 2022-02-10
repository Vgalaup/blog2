<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Length;

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

    #[Route('/categories/{fewId}', name: 'FewCategory')]
    public function postFromCategories(ManagerRegistry $doctrine, string $fewId): Response
    {
        $processedId = explode('-', $fewId);

        for ($i = 0; $i < count($processedId); $i++) {

            $category = $doctrine->getRepository(Category::class)->find($processedId[$i]);
            $categories[] = $category;
        }


        return $this->render('category/categories.html.twig', [
            'categories' => $categories
        ]);
    }


    #[Route('/category', name: 'categories')]
    public function index(): Response
    {
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }
}
