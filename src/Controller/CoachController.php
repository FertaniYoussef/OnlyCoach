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
                'coaches' => $coachRepository->findAll(),'userinfo'=>$this->getUser()
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
