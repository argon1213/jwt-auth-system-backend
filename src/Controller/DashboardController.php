<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class DashboardController extends AbstractController
{
    /**
    * @Route("/api", name="api_")
    */

    // #[Route('/dashboard', name: 'app_dashboard')]
    public function index(): Response
    {
        /**
        * @Route("/dashboard", name="dashboard")
        */
        
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/DashboardController.php',
        ]);
    }
}
