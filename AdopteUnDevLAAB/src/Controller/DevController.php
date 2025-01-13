<?php

namespace App\Controller;

use App\Repository\PosteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\DevRepository;
use App\Repository\NotificationRepository;
use App\Repository\TechnologyDevRepository;
use App\Repository\TechnologyPosteRepository;
use App\Repository\TechnologyRepository;
use App\Repository\UserRepository;
use App\Entity\Notification;

class DevController extends AbstractController
{
    private PosteRepository $posteRepository;
    private TechnologyRepository $technologyRepository;
    private TechnologyPosteRepository $technologyPosteRepository;
    private UserRepository $userRepository;
    private DevRepository $devRepository;
    private TechnologyDevRepository $technologyDevRepository;
    public function __construct(
        PosteRepository $posteRepository, 
        TechnologyRepository $technologyRepository, 
        UserRepository $userRepository,
        DevRepository $devRepository,
        TechnologyPosteRepository $technologyPosteRepository,
        TechnologyDevRepository $technologyDevRepository,
        private NotificationRepository $notificationRepository
    ){
        $this->posteRepository = $posteRepository;
        $this->technologyRepository = $technologyRepository;
        $this->technologyPosteRepository = $technologyPosteRepository;
        $this->devRepository = $devRepository;
        $this->technologyDevRepository = $technologyDevRepository;
        $this->userRepository = $userRepository;
    }
    #[Route('/dev', name: 'app_dev_list')]
    public function index(): Response
    {
        return $this->render('dev/index.html.twig', [
            
        ]);
        
    }
}
