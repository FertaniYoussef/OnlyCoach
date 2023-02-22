<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Coach;
use App\Repository\CoachRepository;
<<<<<<< Updated upstream
#youssef aamel hkeya yijareb feha
=======
use App\Repository\CategorieRepository;

>>>>>>> Stashed changes
class CoachController extends AbstractController
{
    #[Route('/coachs', name: 'app_coach')]
    
        public function index(CoachRepository $coachRepository): Response
        {
            return $this->render('coach/index.html.twig', [
                'coaches' => $coachRepository->findAll(),
            ]);
        }
   
        #[Route('/showcategorie{id}', name: 'showcategorie')]

        public function showcategorie(CoachRepository $repo,$id,CategorieRepository $repository)
        {
            $categorie = $repository->find($id);
            $coachs= $repo->findOneBySomeField($id);
            return $this->render("coach/index.html.twig", [
                'showcategorie'=>$categorie,
                'coachs'=>$coachs
        ]);
        }
        
}
