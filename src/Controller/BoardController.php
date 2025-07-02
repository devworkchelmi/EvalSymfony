<?php

namespace App\Controller;

<<<<<<< HEAD
use App\Entity\Board;
=======
>>>>>>> 95cb2b9e4c384f64e798e3109940de957a6d0251
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
<<<<<<< HEAD

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
=======
>>>>>>> 95cb2b9e4c384f64e798e3109940de957a6d0251
}
