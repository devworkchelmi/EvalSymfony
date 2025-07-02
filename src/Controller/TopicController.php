<?php

namespace App\Controller;

<<<<<<< HEAD
use App\Repository\TopicRepository;
=======
>>>>>>> 95cb2b9e4c384f64e798e3109940de957a6d0251
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TopicController extends AbstractController
{
<<<<<<< HEAD
    // #[Route('/topic', name: 'app_topic')]
    // public function index(): Response
    // {
    //     return $this->render('topic/index.html.twig', [
    //         'controller_name' => 'TopicController',
    //     ]);
    // }

    #[Route('/topic/{id}', name: 'topic_show')]
    public function show(TopicRepository $topicRepository, int $id): Response
    {
        $topic = $topicRepository->find($id);

        if (!$topic) {
            throw $this->createNotFoundException('Sujet introuvable.');
        }

        return $this->render('forum/topic.html.twig', [
            'topic' => $topic,
            'messages' => $topic->getMessages(),
        ]);
    }


=======
    #[Route('/topic', name: 'app_topic')]
    public function index(): Response
    {
        return $this->render('topic/index.html.twig', [
            'controller_name' => 'TopicController',
        ]);
    }
>>>>>>> 95cb2b9e4c384f64e798e3109940de957a6d0251
}
