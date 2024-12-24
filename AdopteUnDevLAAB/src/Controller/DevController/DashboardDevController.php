<?php

namespace App\Controller\DevController;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardDevController extends AbstractController
{
    #[Route('/dev/dashboard', name: 'app_dashboard_dev')]
    public function index(): Response
    {
        return $this->render('dev/dashboard/index.html.twig', [
            'controller_name' => 'DashboardDevController',
        ]);
    }
}
