<?php

namespace App\Controller;

<<<<<<< HEAD
use App\Entity\Category;
=======
>>>>>>> 95cb2b9e4c384f64e798e3109940de957a6d0251
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
<<<<<<< HEAD

     #[Route('/category/{id}', name: 'category_show')]
    public function show(Category $category): Response
    {
        // Tu accèdes à $category->getBoards() directement dans le template
        return $this->render('forum/category.html.twig', [
            'category' => $category,
            'boards' => $category->getBoards(),
        ]);
    }
=======
>>>>>>> 95cb2b9e4c384f64e798e3109940de957a6d0251
}
