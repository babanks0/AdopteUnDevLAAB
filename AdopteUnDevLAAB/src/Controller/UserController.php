<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }

    #[Route('/profil', name: 'app_view_profil')]
    public function viewProfil(): Response
    {
        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }

    #[Route('/profil/edit/{id}', name: 'app_edit_profil')]
    public function editProfil(User $user): Response
    {
        return $this->render('profil/dev/edit.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }
}
