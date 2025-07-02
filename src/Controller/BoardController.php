<?php

namespace App\Controller;

use App\Entity\Board;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BoardController extends AbstractController
{
    #[Route('/board', name: 'app_board')]
    public function index(): Response
    {
        return $this->render('board/index.html.twig', [
            'controller_name' => 'BoardController',
        ]);
    }

    #[Route('/board/{id}', name: 'board_show')]
    public function show(Board $board): Response
    {
        // Si tu as une relation avec des topics
        $topics = $board->getTopics();

        return $this->render('forum/board.html.twig', [
            'board' => $board,
            'topics' => $topics,
        ]);
    }
}
