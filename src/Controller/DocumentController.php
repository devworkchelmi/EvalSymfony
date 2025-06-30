<?php

namespace App\Controller;

use App\Entity\Document;
use App\Form\DocumentType;
use App\Repository\DocumentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/document')]
final class DocumentController extends AbstractController
{
    #[Route('/', name: 'app_document_index', methods: ['GET'])]
    public function index(DocumentRepository $documentRepository): Response
    {
        return $this->render('document/index.html.twig', [
            'documents' => $documentRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_document_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $document = new Document();
        $form = $this->createForm(DocumentType::class, $document);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedFile = $form->get('uploadedFile')->getData();

            if ($uploadedFile) {
                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $uploadedFile->guessExtension();

                try {
                    $uploadedFile->move(
                        $this->getParameter('documents_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors de l’upload du fichier.');
                    return $this->redirectToRoute('app_document_new');
                }

                $document->setFileName($newFilename);
                $document->setCreatedAt(new \DateTimeImmutable());

                $em->persist($document);
                $em->flush();

                $this->addFlash('success', 'Fichier envoyé avec succès.');
                return $this->redirectToRoute('app_document_index');
            }
        }

        return $this->render('document/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/document/{fileName}', name: 'app_document_show')]
    public function show(string $fileName, DocumentRepository $documentRepository): Response
    {
        $document = $documentRepository->findOneBy(['fileName' => $fileName]);

        if (!$document) {
            throw $this->createNotFoundException('Document non trouvé.');
        }

        return $this->render('document/show.html.twig', [
            'document' => $document,
        ]);
    }
    #[Route('/document/{id}/edit', name: 'app_document_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Document $document, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DocumentType::class, $document);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Document mis à jour.');
            return $this->redirectToRoute('app_document_index');
        }

        return $this->render('document/edit.html.twig', [
            'document' => $document,
            'form' => $form,
        ]);
    }

    #[Route('/document/{id}', name: 'app_document_delete', methods: ['POST'])]
    public function delete(Request $request, Document $document, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $document->getId(), $request->request->get('_token'))) {
            $entityManager->remove($document);
            $entityManager->flush();
            $this->addFlash('success', 'Document supprimé.');
        }

        return $this->redirectToRoute('app_document_index');
    }
}
