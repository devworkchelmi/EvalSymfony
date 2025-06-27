<?php

namespace App\Controller;

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
}
