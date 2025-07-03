<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestInfoController extends AbstractController
{
    #[Route('/test/info', name: 'app_test_info')]
    public function index(Request $request): Response
    {
        $prenom = $request->get('prenom');
        $nom = $request->get('nom');
        $age = $request->get('age');

        return $this->render('test_info/index.html.twig', [
            'prenom' => $prenom,
            'nom' => $nom,
            'age' => $age,
        ]);
    }
}
