<?php

namespace App\Controller;


use App\Entity\Notification;
use App\Repository\DevRepository;
use App\Repository\FavorisRepository;
use App\Repository\UserRepository;
use App\Repository\PosteRepository;
use App\Repository\TechnologyRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\NotificationRepository;
use App\Repository\TechnologyDevRepository;
use App\Repository\TechnologyPosteRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
        private NotificationRepository $notificationRepository,
        private EntityManagerInterface $em,
        private FavorisRepository $favorisRepository,
    ) {
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
        $user = $this->getUser();
        $postesData = [];
        $devData = [];
        $users = $this->userRepository->findAllExceptCurrentUser($this->getUser());

        foreach ($users as $user) {
            if ($user->getDev() != null) {
                $technologyDev = $this->technologyDevRepository->findBy(['deleted' => false, 'user' => $user]);
                $datas = [
                    'user' => $user,
                    'technologies' => $technologyDev,
                ];
                $devData[] = $datas;
            }

        }

        $postes = $this->posteRepository->findBy(['deleted' => false]);
        foreach ($postes as $poste) {
            $technology = $this->technologyPosteRepository->findBy(['deleted' => false, 'poste' => $poste]);
            $favoris = $this->favorisRepository->findOneBy(['deleted' => false]);
            $isFavoris = null;
            foreach ($favoris as $favo) {
                if($favo->getPoste() === $poste){
                   $isFavoris = $favo ;
                }
            }
            $datas = [
                'poste' => $poste,
                'favoris' => $isFavoris
            ];
            $postesData[] = $datas;

           
        }
        $notification = $this->em->getRepository(Notification::class)->findOneBy(["user" => $this->getUser(), "view" => false]);
        if ($notification) {
            $notification->setView(true);
            $this->em->persist($notification);
            $this->em->flush();
        }


        return $this->render('dashboard/index.html.twig', [
            'postes' => $postesData,
            'devs' => $devData,
            "notice" => $notification
        ]);

    }
}
