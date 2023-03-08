<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Coach;
use App\Repository\CoachRepository;

class CoachController extends AbstractController
{
    #[Route('/coachs', name: 'app_coach')]
    
        public function index(CoachRepository $coachRepository): Response
        {
            return $this->render('coach/index.html.twig', [
                'coaches' => $coachRepository->findAll(),'userinfo'=>$this->getUser()
            ]);
        }
    
}
