<?php

namespace App\Controller;

use App\Entity\Dev;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DevController extends AbstractController
{
    /**
     * Affiche le formulaire de création d'un profil développeur.
     */
    #[Route('/dev/new', name: 'dev_new', methods: ['GET'])]
    public function new(): Response
    {
        return $this->render('dev/create.html.twig');
    }

    /**
     * Traite la soumission du formulaire de création ou de modification d'un profil développeur.
     */
    #[Route('/dev/save', name: 'dev_save', methods: ['POST'])]
    public function save(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérer l'ID si modification
        $id = $request->request->get('id');
        $dev = $id ? $entityManager->getRepository(Dev::class)->find($id) : new Dev();

        if (!$dev) {
            throw $this->createNotFoundException('Profil développeur introuvable.');
        }

        // Récupérer les données du formulaire
        $dev->setNom($request->request->get('nom'));
        $dev->setPrenoms($request->request->get('prenoms'));
        $dev->setBibliographie($request->request->get('bibliographie'));
        $dev->setSalaireMin((float) $request->request->get('salaireMin'));
        $dev->setVisibilite($request->request->get('visibilite') === 'on');

        // Sauvegarder dans la base de données
        $entityManager->persist($dev);
        $entityManager->flush();

        // Redirection vers la page de visualisation
        return $this->redirectToRoute('dev_show', ['id' => $dev->getId()]);
    }

    /**
     * Affiche les détails d'un profil développeur.
     */
    #[Route('/dev/{id}', name: 'dev_show', methods: ['GET'])]
    public function show(Dev $dev): Response
    {
        return $this->render('dev/show.html.twig', [
            'dev' => $dev,
        ]);
    }

    /**
     * Affiche le formulaire d'édition d'un profil développeur existant.
     */
    #[Route('/dev/{id}/edit', name: 'dev_edit', methods: ['GET'])]
    public function edit(Dev $dev): Response
    {
        return $this->render('dev/edit.html.twig', [
            'dev' => $dev,
        ]);
    }

    /**
     * Supprime un profil développeur.
     */
    #[Route('/dev/{id}/delete', name: 'dev_delete', methods: ['POST'])]
    public function delete(Request $request, Dev $dev, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $dev->getId(), $request->request->get('_token'))) {
            $entityManager->remove($dev);
            $entityManager->flush();

            $this->addFlash('success', 'Profil développeur supprimé avec succès.');
        }

        return $this->redirectToRoute('home');
    }
}
