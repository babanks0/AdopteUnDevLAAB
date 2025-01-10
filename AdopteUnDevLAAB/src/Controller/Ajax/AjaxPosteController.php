<?php

namespace App\Controller\Ajax;

use App\Entity\Favoris;
use App\Repository\CompanyRepository;
use App\Repository\PosteRepository;
use App\Repository\TechnologyPosteRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class AjaxPosteController extends AbstractController
{
    private PosteRepository $posteRepository;
    private TechnologyPosteRepository $technologyPosteRepository;
    private CompanyRepository $companyRepository;
    private UserRepository $userRepository;
    private EntityManagerInterface $manager;
    public function __construct(
        PosteRepository $posteRepository,
        CompanyRepository $companyRepository,
        UserRepository $userRepository,
        EntityManagerInterface $manager,
        TechnologyPosteRepository $technologyPosteRepository,

    ) {
        $this->posteRepository = $posteRepository;
        $this->companyRepository = $companyRepository;
        $this->userRepository = $userRepository;
        $this->manager = $manager;
        $this->technologyPosteRepository = $technologyPosteRepository;  
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

    #[Route('/ajax/findJob', name: 'find_job', options: ['expose' => true])]
    public function findJob(Request $request): JsonResponse
    {
        $motCles = $request->request->get('mots_cles');
        $localisation = $request->request->get('localisation');
        $postes = $this->posteRepository->findBy(['deleted' => false]);
        $datas = [];
        foreach ($postes as $poste) {
            
            $resultat = strstr($poste->getTitre(), $motCles);
            if ($resultat) {
                $datas[] = $poste;
            }
        }
        $posteDatas = [];
        foreach($datas as $item){
            $technology = $this->technologyPosteRepository->findBy(['deleted' => false, 'poste' => $item]);
            $data = [
                'poste' => $item, 
                'technologies' => $technology
            ];
            $posteDatas[] = $data;
        }
        $html = $this->renderView('ajax_poste/jobs.html.twig', [
            'postes'=> $posteDatas,
        ]);
        return new JsonResponse(['success' => true, 'datas' => $html]);
    }
}
