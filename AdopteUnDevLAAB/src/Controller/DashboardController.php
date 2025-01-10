<?php

namespace App\Controller;


use App\Repository\DevRepository;
use App\Repository\NotificationRepository;
use App\Repository\PosteRepository;
use App\Repository\TechnologyDevRepository;
use App\Repository\TechnologyPosteRepository;
use App\Repository\TechnologyRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Notification;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
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
    #[Route('/', name: 'app_dashboard')]
    public function index(): Response
    {
        $postesData = [];
        $devData  = [];
        $users = $this->userRepository->findBy(['deleted' => false]);
        foreach($users  as $user){
            $technologyDev =  $this->technologyDevRepository->findBy(['deleted' => false, 'user' => $user]);
            $datas = [
                'user' => $user, 
                'technologies' => $technologyDev,
            ];
            $devData[] = $datas;
        }

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
            'postes'=> $postesData,
            'devs' => $devData,
            "notice"=> $notification
        ]);
        
    }
}
