<?php

namespace App\Controller;

use App\Entity\Board;
use App\Form\TopicType;
use App\Entity\Message;
use App\Entity\Topic;
use App\Repository\TopicRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TopicController extends AbstractController
{

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

    #[Route('/board/{id}/topic/new', name: 'topic_new')]
    public function new(Request $request, Board $board, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $topic = new Topic();
        $form = $this->createForm(TopicType::class, $topic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $topic->setBoard($board);
            $topic->setAuthor($this->getUser());
            $topic->setCreatedAt(new \DateTime());

            // Création du message initial à partir du contenu du topic
            $message = new Message();
            $message->setContent($topic->getContent());
            $message->setAuthor($this->getUser());
            $message->setCreatedAt(new \DateTime());
            $message->setTopic($topic);

            $em->persist($topic);
            $em->persist($message); // enregistrement du msg
            $em->flush();

            $this->addFlash('success', 'Le sujet a été créé avec succès.');

            return $this->redirectToRoute('topic_show', [
                'id' => $topic->getId(),
            ]);
        }

        return $this->render('topic/new.html.twig', [
            'form' => $form->createView(),
            'board' => $board,
        ]);
    }

}
