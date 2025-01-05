<?php

namespace App\Controller\Ajax;

use App\Entity\Favoris;
use App\Repository\CompanyRepository;
use App\Repository\PosteRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class AjaxPosteController extends AbstractController
{
    private PosteRepository $posteRepository;
    private CompanyRepository $companyRepository;
    private UserRepository $userRepository;
    private EntityManagerInterface $manager;
    public function __construct(
        PosteRepository $posteRepository,
        CompanyRepository $companyRepository,
        UserRepository $userRepository,
        EntityManagerInterface $manager
     ){
        $this->posteRepository = $posteRepository;
        $this->companyRepository = $companyRepository;
        $this->userRepository = $userRepository;
        $this->manager = $manager;
    }
    #[Route('/ajax/poste', name: 'add_favoris_poste', options: ['expose' => true])]
    public function favoris(Request $request): JsonResponse
    {
        $poste = $this->posteRepository->findOneBy(['deleted' => false, 'id' =>  $request->request->get('poste_id')]);
        $company =  $this->companyRepository->findOneBy(['deleted' => false, 'id' => $request->request->get('user_id')]);
        $favoris = new Favoris();
        $favoris->setPoste($poste)->setUser($this->userRepository->findOneBy(['deleted' => false, 'company' => $company]));
        $this->manager->persist($favoris);
        $poste->setFavoris(true);
        $this->manager->flush();
        return new JsonResponse(['success' => true]);
    }
}
