<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Coach;
use App\Entity\Abonnement;
use App\Repository\CoachRepository;
use App\Repository\CategorieRepository;
use App\Repository\AbonnementRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use Doctrine\Persistence\ManagerRegistry;



use App\Repository\OffreRepository;

class CoachController extends AbstractController
{
    #[Route('/coachs', name: 'app_coach')]
    
    public function index(CoachRepository $coachRepository,AbonnementRepository $abonnementRepository): Response
    {
        $abon=$abonnementRepository->findAll();

        return $this->render('coach/index.html.twig', [
            'coaches' => $coachRepository->findAll(),'userinfo'=>$this->getUser(),'abon'=>$abon
        ]);
    }

    #[Route('/coachs/fav/{id}', name: 'app_coach_fav')]

    public function inverFav(CoachRepository $coachRepository,AbonnementRepository $abonnementRepository,int $id,ManagerRegistry $doctrine): Response
    {
        $user = $this->getUser();
        $abonnement=$abonnementRepository->findAbonnementByAdherentAndCoach($user->getId(),$id);
        $abonnement->setisFav(!$abonnement->isisFav());
        $entityManager = $doctrine->getManager();
        $entityManager=$doctrine->getManager();
        $entityManager->persist($abonnement);
        $entityManager->flush();
        $abon=$abonnementRepository->findAll();
        $coachRepository = $entityManager->getRepository(Coach::class);

        $query = $coachRepository->createQueryBuilder('c')
            ->join('c.id_abonnement', 'a')
            ->where('a.user = :userId')
            ->setParameter('userId',$user->getId() )
            ->getQuery();

        $coaches = $query->getResult();
        return $this->render('coach/fav.html.twig', [
            'coaches' => $coaches,'userinfo'=>$this->getUser(),'abon'=>$abon
        ]);
    }


    #[Route('/coachs/fave/home', name: 'app_coach_fav_home')]

    public function HomeFav(CoachRepository $coachRepository,AbonnementRepository $abonnementRepository,ManagerRegistry $doctrine): Response
    {
        $user = $this->getUser();
        $abon=$abonnementRepository->findAll();
        $entityManager = $doctrine->getManager();
        $coachRepository = $entityManager->getRepository(Coach::class);

        $query = $coachRepository->createQueryBuilder('c')
            ->join('c.id_abonnement', 'a')
            ->where('a.user = :userId')
            ->setParameter('userId',$user->getId() )
            ->getQuery();

        $coaches = $query->getResult();
        return $this->render('coach/fav.html.twig', [
            'coaches' => $coaches,'userinfo'=>$this->getUser(),'abon'=>$abon
        ]);
    }

    #[Route('/coachs/{id}', name: 'app_coach_tri')]
        public function indexcategorie($id,CoachRepository $coachRepository, CategorieRepository $categorieRepository): Response
        {
    // Récupérer la catégorie courante en fonction de l'ID fourni
        $currentCategory = $categorieRepository->find($id);

    // Récupérer les coaches triés par catégorie
        $coaches = $coachRepository->findAllByCategory($id);
        return $this->render('coach/filterBycategory.html.twig', [
        'coaches' => $coaches,
        'currentCategory' => $currentCategory,
        ]);
        }
    
    #[Route('coach/search', name: 'app_coach_search', methods: ['GET', 'POST'])]
    public function search( Request $request, CoachRepository $coachRepository)
    {
        $query = $request->get('query');
        $coach = $coachRepository->search($query);
        //return new JsonResponse(['data' => $data]);
        $template = $this->render('coach/afffichage.html.twig', ['coaches' => $coach]);
            return $this->json(["message" => "Model supprimée avec Succès", 'template' =>  $template, "result"=>  $query ], 201, []);
    }



}
