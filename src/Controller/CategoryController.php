<?php

namespace App\Controller;

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

    #[Route('/category/{id}', name: 'app_category')]
    public function showCategory(int $id): Response
    {
        //donnée faker

        $boards = [
            ['id' => 1, 'name' => 'General'],
            ['id' => 2, 'name' => 'RH'],
            ['id' => 3, 'name' => 'Technique'],
            ['id' => 4, 'name' => 'Employer'],
        ]; 

        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
            'boards' => $boards,
        ]);
    }
}
