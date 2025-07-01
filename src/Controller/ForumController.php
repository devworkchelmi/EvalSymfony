<?php

namespace App\Controller;

use App\Entity\Board;
use App\Entity\Category;
use App\Entity\Topic;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ForumController extends AbstractController
{
#[Route('/forum', name: 'forum_index')]
public function index(CategoryRepository $categoryRepository): Response
{
    $categories = $categoryRepository->findAll();

    return $this->render('forum/index.html.twig', [
        'categories' => $categories,
    ]);
    }

    #[Route('/category/{id}', name: 'category')]
    public function showCategory(Category $category): Response
    {
        return $this->render('forum/category.html.twig', [
            'category' => $category,
        ]);
    }

    #[Route('/board/{id}', name: 'board_show')]
    public function showBoard(Board $board): Response
    {
        return $this->render('forum/board.html.twig', [
            'board' => $board,
        ]);
    }

    #[Route('/topic/{id}', name: 'topic_show')]
    public function showTopic(Topic $topic): Response
    {
        return $this->render('forum/topic.html.twig', [
            'topic' => $topic,
            'messages' => $topic->getMessages(), // si relation bien définie
        ]);
    }
}
