<?php

namespace App\Controller;

<<<<<<< HEAD
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

    #[Route('/category/{id}', name: 'forum_category')]
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
=======
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ForumController extends AbstractController
{
    #[Route('/forum', name: 'app_forum')]
    public function index(): Response
    {
        // return $this->render('forum/index.html.twig', [
        //     'controller_name' => 'ForumController',
        // ])
        // ;

        return $this->render('forum/topic.html.twig', [
            'topic' => 'Sujet fictif',
            'messages' => [],
        ]);
    }

    public function new(Request $request, FileUploader $fileUploader)
    {
        $form = '$form';

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('attachment')->getData();
            if ($file) {
                $fileName = $fileUploader->upload($file);
                // stocker $fileName dans l'entité
            }
        }
    }
>>>>>>> 95cb2b9e4c384f64e798e3109940de957a6d0251
}
