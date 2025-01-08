<?php

namespace App\Controller;

use App\Entity\Notification;
use App\Repository\PosteRepository;
use App\Repository\TechnologyRepository;
use App\Repository\NotificationRepository;
use App\Repository\TechnologyPosteRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DashboardController extends AbstractController
{
    private PosteRepository $posteRepository;
    private TechnologyRepository $technologyRepository;
    private TechnologyPosteRepository $technologyPosteRepository;
    public function __construct(
        PosteRepository $posteRepository, 
        TechnologyRepository $technologyRepository, 
        TechnologyPosteRepository $technologyPosteRepository,
        private NotificationRepository $notificationRepository
    ){
        $this->posteRepository = $posteRepository;
        $this->technologyRepository = $technologyRepository;
        $this->technologyPosteRepository = $technologyPosteRepository;
    }
    #[Route('/', name: 'app_dashboard')]
    public function index(): Response
    {
        $postesData = [];
        $postes = $this->posteRepository->findBy(['deleted' => false]);
        foreach($postes as $poste){
            $technology = $this->technologyPosteRepository->findBy(['deleted' => false, 'poste' => $poste]);
            $datas = [
                'poste' => $poste, 
                'technologies' => $technology
                
            ];
            $postesData[] = $datas;
        }

        $notification = $this->notificationRepository->findOneBy(["user"=>$this->getUser(),"view"=>false]);

        return $this->render('dashboard/index.html.twig', [
            'postes'=> $postesData,"notice"=> $notification
        ]);
    }
}
