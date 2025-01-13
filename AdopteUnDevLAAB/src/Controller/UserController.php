<?php

namespace App\Controller;

use App\Entity\Dev;
use App\Entity\User;
use App\Entity\TechnologyDev;
use App\Form\DevProfilFormType;
use App\Form\CompanyProfilFormType;
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
    // #[IsGranted('ROLE_USER')]
    public function viewProfil(Request $request): Response
    {
        $userId = $request->get('id',null);
        $user = $this->em->getRepository(User::class)->findOneById($userId) ?? $this->getUser();
        $technologies = $this->technologyDevRepository->findByUser($user);

        if ($userId && $this->getUser() && $userId != $this->getUser()->getId() ) {
            $user->setView($user->getView()+1);
            $this->em->persist($user);
            $this->em->flush();
        }

        if (!$userId && !$this->getUser()) return $this->redirectToRoute('app_login');
        
        return $this->render('profil/dev/view.html.twig', [
            'technologies' =>   $technologies,
            'user'         =>   $user
        ]);
    }



    #[Route('/profil/edit', name: 'app_edit_profil')]
    #[IsGranted('ROLE_USER')]
    public function editProfil(Request $request): Response
    {
        $user = $this->getUser();
        if ($user->getDev()) {

            $technologies = $this->technologyRepository->findBy(['deleted' => false]);

            $userTechnologies = $this->technologyDevRepository->findBy(['user' => $user]);

            $userTechnologyIds = array_map(function ($technologyDev) {
                return $technologyDev->getTechnology(); 
            }, $userTechnologies);

            $form = $this->createForm(DevProfilFormType::class, $user->getDev(), [
                'technologies' => $technologies,
                'localisation' => $user->getLocalisation() ?? 'Paris',
                'default_technologies' => $userTechnologyIds ?? [],
            ]);

            $link = 'profil/dev/edit.html.twig';
        }
        else {
            $form = $this->createForm(CompanyProfilFormType::class, $user->getCompany(), [
                'localisation' => $user->getLocalisation() ?? 'Paris',
            ]);

            $link = 'profil/company/edit.html.twig';
        }
        
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            // Si un fichier avatar a été téléchargé
            $avatarFile = $form->get('avatar')->getData();
            if ($avatarFile) {
                $this->addAvatar($avatarFile,$user);
            }

            if($user->getDev()) $this->editDev($user,$form,$userTechnologies);

            $localisation = $form->get('localisation')->getData();
    
            $user->setLocalisation($localisation); 
            $this->em->persist($user);
            $this->em->flush();

            $this->addFlash('success', 'Profil mis à jour avec succès');
            return $this->redirectToRoute('app_edit_profil');
        }

        return $this->render($link, [
            'form' => $form,
            'user' => $user
        ]);
    }

    private function editDev(User $user,$form,$userTechnologies){

        $technologies = $form->get('tech')->getData();
        if ($technologies) {

            array_map(function ($technologyDev) {
                $this->technologyDevRepository->remove($technologyDev);
            }, $userTechnologies);

            foreach ($technologies as $technology) {
                $technologyDev = new TechnologyDev();

                    $technologyDev->setTechnology($technology);
                    $technologyDev->setUser($user);
                    $this->em->persist($technologyDev);
            }
        }

    }

    private function addAvatar($avatarFile,User $user){

            $newFilename = uniqid().'.'.$avatarFile->guessExtension();

            try {
                $avatarFile->move(
                    $this->getParameter('avatars_directory'),
                    $newFilename
                );
            } catch (IOExceptionInterface $exception) {
                $this->addFlash('error', 'Erreur lors de l\'envoi de l\'avatar.');
                return $this->redirectToRoute('app_edit_profil');
            }

            $user->setAvatar($newFilename);
            $this->em->persist($user);
    }
}
