<?php

namespace App\Controller;

use App\Entity\Company;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CompanyController extends AbstractController
{
    #[Route('/company/new', name: 'company_new', methods: ['GET'])]
    public function new(): Response
    {
        return $this->render('company/create.html.twig');
    }

    #[Route('/company/save', name: 'company_save', methods: ['POST'])]
    public function save(Request $request, EntityManagerInterface $entityManager): Response
    {
        $id = $request->request->get('id');
        $company = $id ? $entityManager->getRepository(Company::class)->find($id) : new Company();

        if (!$company) {
            throw $this->createNotFoundException('Profil entreprise introuvable.');
        }

        $company->setName($request->request->get('name'));
        $company->setLocation($request->request->get('location'));
        $company->setDescription($request->request->get('description'));
        $company->setTechnologies($request->request->get('technologies'));
        $company->setSalaryRange((float) $request->request->get('salaryRange'));

        $entityManager->persist($company);
        $entityManager->flush();

        return $this->redirectToRoute('company_show', ['id' => $company->getId()]);
    }

    #[Route('/company/{id}', name: 'company_show', methods: ['GET'])]
    public function show(Company $company): Response
    {
        return $this->render('company/show.html.twig', [
            'company' => $company,
        ]);
    }

    #[Route('/company/{id}/edit', name: 'company_edit', methods: ['GET'])]
    public function edit(Company $company): Response
    {
        return $this->render('company/edit.html.twig', [
            'company' => $company,
        ]);
    }

    #[Route('/company/{id}/delete', name: 'company_delete', methods: ['POST'])]
    public function delete(Request $request, Company $company, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $company->getId(), $request->request->get('_token'))) {
            $entityManager->remove($company);
            $entityManager->flush();

            $this->addFlash('success', 'Profil entreprise supprimé avec succès.');
        }

        return $this->redirectToRoute('home');
    }
}
