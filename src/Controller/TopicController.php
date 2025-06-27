<?php

namespace App\Controller;

use App\Entity\Topic;
use App\Repository\TopicRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TopicController extends AbstractController
{
    #[Route('/topic', name: 'app_topic')]
    public function index(): Response
    {
        return $this->render('topic/index.html.twig', [
            'controller_name' => 'TopicController',
        ]);
    }

    #[Route('/topic/{id}', name: 'topic_show')]
    public function show(TopicRepository $topicRepository, int $id): Response
    {
        $topic = $topicRepository->find($id); // ⚠️ ici on ne charge pas les messages

        if (!$topic) {
            throw $this->createNotFoundException('Sujet introuvable.');
        }

        return $this->render('forum/topic.html.twig', [
            'topic' => $topic,
            'messages' => $topic->getMessages(),
        ]);
    }


}
