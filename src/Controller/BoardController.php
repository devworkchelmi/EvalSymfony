<?php

namespace App\Controller;

use App\Repository\BoardRepository;
use App\Entity\Board;
use App\Repository\TopicRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BoardController extends AbstractController
{
    #[Route('/board', name: 'app_board')]
    public function index(BoardRepository $boardRepository): Response
    {
        $boards = $boardRepository->findAll();

        return $this->render('board/index.html.twig', [
            'boards' => $boards,
        ]);
    }

    #[Route('/board/{id}', name: 'forum_board')]
    public function show(Board $board, TopicRepository $topicRepository): Response
    {
        $topics = $topicRepository->findBy(['board' => $board], ['createdAt' => 'DESC']);

        return $this->render('board/show.html.twig', [
            'board' => $board,
            'topics' => $topics,
        ]);
    }
}
