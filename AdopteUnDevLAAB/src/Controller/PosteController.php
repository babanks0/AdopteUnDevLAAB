<?php

namespace App\Controller;

use App\Entity\Candidacture;
use App\Entity\Poste;
use App\Form\PosteFormType;
use App\Repository\CandidactureRepository;
use App\Repository\FavorisRepository;
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
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class PosteController extends AbstractController
{
    private NiveauEtudeRepository $niveauEtudeRepository;
    private NiveauEtudePosteRepository $niveauEtudePosteRepository;
    private TechnologyRepository $technologyRepository;
    private PosteRepository $posteRepository;
    private TechnologyPosteRepository $technologyPosteRepository;
    private EntityManagerInterface $manager;
    private UserRepository $userRepository;
    private FavorisRepository $favorisRepository;
    private CandidactureRepository $candidactureRepository;

    public function __construct(
        NiveauEtudeRepository $niveauEtudeRepository,
        NiveauEtudePosteRepository $niveauEtudePosteRepository,
        TechnologyRepository $technologyRepository,
        TechnologyPosteRepository $technologyPosteRepository,
        EntityManagerInterface $manager,
        PosteRepository $posteRepository,
        UserRepository $userRepository,
        FavorisRepository $favorisRepository,
        CandidactureRepository $candidactureRepository
    ) {
        $this->niveauEtudeRepository = $niveauEtudeRepository;
        $this->niveauEtudePosteRepository = $niveauEtudePosteRepository;
        $this->technologyRepository = $technologyRepository;
        $this->manager = $manager;
        $this->posteRepository = $posteRepository;
        $this->technologyPosteRepository = $technologyPosteRepository;
        $this->userRepository = $userRepository;
        $this->favorisRepository = $favorisRepository;
        $this->candidactureRepository = $candidactureRepository;
    }

    #[Route('/poste', name: 'app_poste')]
    #[IsGranted('ROLE_USER')]
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
            $poste->setFavoris(false);
            $this->manager->persist($poste);
            foreach ($request->request->all('states') as $item) {
                $technologyPoste = new TechnologyPoste();
                $technologyPoste->setPoste($poste)
                    ->setTechnology($this->technologyRepository->find($item));
                $this->manager->persist($technologyPoste);
            }
            foreach ($request->request->all('etudes') as $item) {
                $niveauEtudePoste = new NiveauEtudePoste();
                $niveauEtudePoste->setPoste($poste)
                    ->setNiveauEtude($this->niveauEtudeRepository->find($item));
                $this->manager->persist($niveauEtudePoste);
            }
            $this->manager->flush();
            $this->addFlash('success', 'Votre poste a été enregistré avec succès.');
            $this->sendNotification($poste);
            return $this->redirectToRoute('app_dashboard');
        }
        return $this->render('poste/create.html.twig', [
            'niveaux' => $niveaux,
            'technologies' => $technologies,
            'form' => $form,
        ]);
    }

    #[Route('/job', name: 'app_find_job')]
    public function findJob(): Response
    {

        $motCles = $_REQUEST['mot_cles'] ?? '';
        $localisation = $_REQUEST['localisation'] ?? '';
        $postesData = [];
        if ($motCles === "" && $localisation === "") {
            $postes = $this->posteRepository->findBy(['deleted' => false]);
            foreach ($postes as $poste) {
                $technology = $this->technologyPosteRepository->findBy(['deleted' => false, 'poste' => $poste]);
                $datas = [
                    'poste' => $poste,
                    'technologies' => $technology
                ];
                $postesData[] = $datas;
            }
        } else {
            $postes = $this->posteRepository->findBy(['deleted' => false]);
            $datas = [];
            foreach ($postes as $poste) {
                $titreCorrespond = $motCles ? stripos($poste->getTitre(), $motCles) !== false : true;
                $localisationCorrespond = $localisation ? stripos($poste->getLocalisation(), $localisation) !== false : true;
                if (
                    ($motCles && !$localisation && $titreCorrespond) ||
                    (!$motCles && $localisation && $localisationCorrespond) ||
                    ($motCles && $localisation && $titreCorrespond && $localisationCorrespond)
                ) {
                    $datas[] = $poste;
                }
            }
            foreach ($datas as $item) {
                $technology = $this->technologyPosteRepository->findBy(['deleted' => false, 'poste' => $item]);
                $data = [
                    'poste' => $item,
                    'technologies' => $technology
                ];
                $postesData[] = $data;
            }
       
        }

        return $this->render('poste/trouver_job.html.twig', [
            'postes' => $postesData
        ]);
    }

    private function sendNotification(Poste $post)
    {
        $technologiesPosts = $this->manager->getRepository(TechnologyPoste::class)->findBy(['poste' => $post]);
        array_map(function ($technology) {

            $technologyDevs = $this->manager->getRepository(TechnologyDev::class)->findBy(['technology' => $technology->getTechnology()]);
            foreach ($technologyDevs as $technologyDev) {
                $post = $technology->getPoste();
                $user = $technologyDev->getUser();

                $notification = $this->manager->getRepository(Notification::class)->findOneBy(['user' => $user, 'post' => $post]);

                if (!$notification && $user->getDev()->getExperienceLevel() >= $post->getNiveauExperience() && (int) $user->getDev()->getSalaireMin() <= (int) $post->getSalaire()) {
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

    #[Route('/poste_by_company', name: 'app_poste_by_company')]
    public function posteByCompany(): Response
    {
        $postesData = [];

        $user = $this->getUser();
        if ($user->getCompany() != null) {
            $postes = $this->posteRepository->findBy(['deleted' => false, 'company' => $user->getCompany()]);
            foreach ($postes as $poste) {
                $technology = $this->technologyPosteRepository->findBy(['deleted' => false, 'poste' => $poste]);
                $datas = [
                    'poste' => $poste,
                    'technologies' => $technology
                ];
                $postesData[] = $datas;
            }
        }
        return $this->render('poste/poste_by_company.html.twig', [
            'postes' => $postesData
        ]);

    }

    #[Route('/poste_by_company', name: 'app_poste_favoris_by_company')]
    public function posteFavorisByCompany(): Response
    {
        $postesData = [];

        $user = $this->getUser();
        if ($user->getCompany() != null) {
            $postes = $this->posteRepository->findBy(['deleted' => false, 'company' => $user->getCompany()]);
            foreach ($postes as $poste) {
                $technology = $this->technologyPosteRepository->findBy(['deleted' => false, 'poste' => $poste]);
                $datas = [
                    'poste' => $poste,
                    'technologies' => $technology
                ];
                $postesData[] = $datas;
            }
        }
        return $this->render('poste/poste_by_company.html.twig', [
            'postes' => $postesData
        ]);
    }

    #[Route('/poste_details/{id}', name: 'app_details_poste')]
    public function detailsPoste(Poste $poste): Response
    {
        $user = $this->userRepository->findOneBy(['company' => $poste->getCompany()]);
        return $this->render('poste/details.html.twig', [
            'poste' => $poste,
            'user' => $user
        ]);
    }

    #[Route('/favoris', name: 'app_favoris_poste')]
    #[IsGranted('ROLE_USER')]
    public function listeFavoris(): Response
    {
        $user = $this->getUser();
        $postesData = [];
        $favoris = $this->favorisRepository->findBy([]);
        foreach ($favoris as $poste) {
            $technology = $this->technologyPosteRepository->findBy(['deleted' => false, 'poste' => $poste->getPoste()]);
            $datas = [
                'poste' => $poste->getPoste(),
                'technologies' => $technology
            ];
            $postesData[] = $datas;
        }
        return $this->render('poste/favoris.html.twig', [
            'postes' => $postesData,

        ]);
    }


    #[Route('/candidacture/{id}', name: 'app_candidacture_poste')]
    #[IsGranted('ROLE_USER')]
    public function candidacture(Poste $poste): Response
    {
        $user = $this->getUser();
        $candidacture = new Candidacture();
        $candidacture->setPoste($poste);
        $candidacture->setUser($user);
        $this->manager->persist($candidacture);
        $this->manager->flush();
        return $this->redirectToRoute('app_dashboard');
    }

    #[Route('/candidactures', name: 'app_list_candidacture_poste')]
    #[IsGranted('ROLE_USER')]
    public function listeCandidacture(): Response
    {
        $user = $this->getUser();
        $candidactures = $this->candidactureRepository->findBy(['user' => $user,]);

        $postesData = [];

        foreach ($candidactures as $candidacture) {
            $technology = $this->technologyPosteRepository->findBy(['deleted' => false, 'poste' => $candidacture->getPoste()]);
            $datas = [
                'poste' => $candidacture->getPoste(),
                'technologies' => $technology
            ];
            $postesData[] = $datas;
        }
        return $this->render('poste/candidactures.html.twig', [
            'postes' => $postesData
        ]);
    }
}
