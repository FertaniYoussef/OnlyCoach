<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Coach;
use App\Repository\CoachRepository;
use App\Repository\CategorieRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;




class CoachController extends AbstractController
{
    #[Route('/coachs', name: 'app_coach')]
    
    public function index(CoachRepository $coachRepository): Response
    {
        return $this->render('coach/index.html.twig', [
            'coaches' => $coachRepository->findAll(),
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
