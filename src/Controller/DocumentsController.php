<?php

namespace App\Controller;

use App\Entity\Document;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Dom\DocumentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DocumentsController extends AbstractController
{
    public function new(Request $request, EntityManagerInterface $em, FileUploader $fileUploader): Response
    {
        $document = new Document();
        $form = $this->createForm(DocumentType::class, $document);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('upload')->getData();
            if ($file) {
                $filename = $fileUploader->upload($file);
                $document->setFileName($filename);
            }
            $em->persist($document);
            $em->flush();

            $this->addFlash('success', 'Fichier uploadé !');
            return $this->redirectToRoute('document_new');
        }

        return $this->render('document/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
