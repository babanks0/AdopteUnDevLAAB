<?php

namespace App\Controller;

use App\Entity\NiveauEtudePoste;
use App\Entity\Poste;
use App\Entity\TechnologyPoste;
use App\Form\PosteFormType;
use App\Repository\NiveauEtudePosteRepository;
use App\Repository\NiveauEtudeRepository;
use App\Repository\TechnologyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PosteController extends AbstractController
{
    private NiveauEtudeRepository $niveauEtudeRepository;
    private NiveauEtudePosteRepository $niveauEtudePosteRepository;
    private TechnologyRepository $technologyRepository;
    private EntityManagerInterface $manager;
    public function __construct(
        NiveauEtudeRepository $niveauEtudeRepository,
        NiveauEtudePosteRepository $niveauEtudePosteRepository,
        TechnologyRepository $technologyRepository,
        EntityManagerInterface $manager,
    ) {
        $this->niveauEtudeRepository = $niveauEtudeRepository;
        $this->niveauEtudePosteRepository = $niveauEtudePosteRepository;
        $this->technologyRepository = $technologyRepository;
        $this->manager = $manager;
    }

    #[Route('/poste', name: 'app_poste')]
    public function index(Request $request): Response
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
}
