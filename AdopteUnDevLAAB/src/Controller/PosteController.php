<?php

namespace App\Controller;

use App\Entity\Poste;
use App\Form\PosteFormType;
use App\Repository\NiveauEtudePosteRepository;
use App\Repository\NiveauEtudeRepository;
use App\Repository\PosteRepository;
use App\Repository\TechnologyPosteRepository;
use App\Entity\Notification;
use App\Entity\TechnologyDev;
use App\Entity\TechnologyPoste;
use App\Entity\NiveauEtudePoste;
use App\Entity\User;
use App\Repository\TechnologyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PosteController extends AbstractController
{
    private NiveauEtudeRepository $niveauEtudeRepository;
    private NiveauEtudePosteRepository $niveauEtudePosteRepository;
    private TechnologyRepository $technologyRepository;
    private PosteRepository $posteRepository;
    private TechnologyPosteRepository $technologyPosteRepository;
    private EntityManagerInterface $manager;
    public function __construct(
        NiveauEtudeRepository $niveauEtudeRepository,
        NiveauEtudePosteRepository $niveauEtudePosteRepository,
        TechnologyRepository $technologyRepository,
        TechnologyPosteRepository $technologyPosteRepository,
        EntityManagerInterface $manager,
        PosteRepository $posteRepository,
    ) {
        $this->niveauEtudeRepository = $niveauEtudeRepository;
        $this->niveauEtudePosteRepository = $niveauEtudePosteRepository;
        $this->technologyRepository = $technologyRepository;
        $this->manager = $manager;
        $this->posteRepository = $posteRepository;
        $this->technologyPosteRepository = $technologyPosteRepository;
    }

    #[Route('/poste', name: 'app_poste')]
    public function createPoste(Request $request): Response
    {
        $poste = new Poste();
        $form = $this->createForm(PosteFormType::class, $poste);
        $form->handleRequest($request);
        $niveaux = $this->niveauEtudeRepository->findBy(['deleted' => false]);
        $technologies = $this->technologyRepository->findBy(['deleted' => false]);
        if ($form->isSubmitted()) {
            $poste->setLocalisation($request->request->get('localisation'));
            $poste->setDescription($request->request->get('description'));
            $poste->setCompany($this->getUser()->getCompany());
            $this->manager->persist($poste);
            foreach ($request->request->all('states') as $item) {
                $technologyPoste = new TechnologyPoste();
                $technologyPoste->setPoste($poste)
                    ->setTechnology($this->technologyRepository->find((int) $item));
                $this->manager->persist($technologyPoste);
            }
            foreach ($request->request->all('etudes') as $item) {
                $niveauEtudePoste = new NiveauEtudePoste();
                $niveauEtudePoste->setPoste($poste)
                    ->setNiveauEtude($this->niveauEtudeRepository->find((int) $item));
                $this->manager->persist($niveauEtudePoste);
            }
            $this->manager->flush();
            $this->addFlash('success', 'Votre poste a été enregistré avec succès.');
            return $this->redirectToRoute('app_dashboard');
        }
        return $this->render('poste/index.html.twig', [
            'niveaux' => $niveaux,
            'technologies' => $technologies,
            'form' => $form,
        ]);
    }

    #[Route('/job', name: 'app_find_job')]
    public function findJob(): Response
    {
        $postesData = [];
        $postes = $this->posteRepository->findBy(['deleted' => false]);
        foreach ($postes as $poste) {
            $technology = $this->technologyPosteRepository->findBy(['deleted' => false, 'poste' => $poste]);
            $datas = [
                'poste' => $poste,
                'technologies' => $technology
            ];
            $postesData[] = $datas;
        }
        return $this->render('poste/trouver_job.html.twig', [
            'postes' => $postesData
        ]);
    }

    private function sendNotification(Poste $post)
    {
        $technologiesPosts = $this->manager->getRepository(TechnologyPoste::class)->findBy(['poste'=>$post]);

        array_map(function ($technology) {
            $technologyDevs =  $this->manager->getRepository(TechnologyDev::class)->findBy(['technology'=>$technology]);
            foreach ($technologyDevs as $technologyDev) {

                $user = $this->manager->getRepository(User::class)->findOneByDev($technologyDev->getDev());

                $notification = $this->manager->getRepository(Notification::class)->find0neBy(['user'=>$user,'post'=>$post]);

                if(!$notification){
                    $notification = new Notification();
                    $notification->setUser($user);
                    $notification->setPost($post);
                    $notification->setView(false);
                }
                
                $this->manager->persist($notification);
            }
        }, $technologiesPosts);
        
        $this->manager->flush();

    }
}
