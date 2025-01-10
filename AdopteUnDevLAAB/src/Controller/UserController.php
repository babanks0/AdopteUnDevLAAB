<?php

namespace App\Controller;

use App\Entity\Dev;
use App\Entity\User;
use App\Entity\TechnologyDev;
use App\Form\DevProfilFormType;
use App\Repository\TechnologyRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TechnologyDevRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    public function __construct(private TechnologyRepository $technologyRepository,private TechnologyDevRepository $technologyDevRepository, private EntityManagerInterface $em){

    }
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
    #[IsGranted('ROLE_USER')]
    public function editProfil(User $user,Request $request): Response
    {
        $technologies = $this->technologyRepository->findBy(['deleted' => false]);

        $dev = $user ? $user->getDev() : null;

        $userTechnologies = $this->technologyDevRepository->findBy(['dev' => $user->getDev() ?? null]);

        $userTechnologyIds = array_map(function ($technologyDev) {
            return $technologyDev->getTechnology(); 
        }, $userTechnologies);

        $form = $this->createForm(DevProfilFormType::class, $dev, [
            'technologies' => $technologies,
            'localisation' => $dev ? $user->getLocalisation() : 'Paris',
            'default_technologies' => $userTechnologyIds ?? [],
        ]);

        // Soumission du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            // Si un fichier avatar a été téléchargé
            $avatarFile = $form->get('avatar')->getData();
            if ($avatarFile) {
                // Définir le nom du fichier
                $newFilename = uniqid().'.'.$avatarFile->guessExtension();

                try {
                    // Déplacer le fichier dans le dossier 'uploads/avatars'
                    $avatarFile->move(
                        $this->getParameter('avatars_directory'),
                        $newFilename
                    );
                } catch (IOExceptionInterface $exception) {
                    $this->addFlash('error', 'Erreur lors de l\'envoi de l\'avatar.');
                    return $this->redirectToRoute('app_edit_profil', ['id' => $user->getId()]);
                }

                // Mettre à jour l'avatar de l'utilisateur
                $user->setAvatar($newFilename);
            }

            // Récupérer les technologies sélectionnées
            $technologies = $form->get('tech')->getData();
            if ($technologies) {

                array_map(function ($technologyDev) {
                    $this->technologyDevRepository->remove($technologyDev);
                }, $userTechnologies);
                // On peut traiter les technologies associées à Dev ici
                // Exemple : ajouter les technologies à la relation
                foreach ($technologies as $technology) {
                    $technologyDev = new TechnologyDev();

                        $technologyDev->setTechnology($technology);
                        $technologyDev->setDev($dev);
                        $this->em->persist($technologyDev);
                }
            }

            $localisation = $form->get('localisation')->getData();
    
            $user->setLocalisation($localisation); 
            $this->em->persist($user);
            $this->em->flush();

            // Rediriger ou afficher un message de succès
            $this->addFlash('success', 'Profil mis à jour avec succès');
            return $this->redirectToRoute('app_edit_profil', ['id' => $user->getId()]);
        }

        return $this->render('profil/dev/edit.html.twig', [
            'form' => $form,
            'user' => $user
        ]);
    }
}
