<?php

namespace App\Controller;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_category')]
    public function index(): Response
    {
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }

     #[Route('/category/{id}', name: 'category_show')]
    public function show(Category $category): Response
    {
        // Tu accèdes à $category->getBoards() directement dans le template
        return $this->render('forum/category.html.twig', [
            'category' => $category,
            'boards' => $category->getBoards(),
        ]);
    }
}
