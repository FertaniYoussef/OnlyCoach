<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use App\Entity\Coach;
use App\Repository\CoachRepository;

class LandingPageController extends AbstractController
{
    #[Route('/', name: 'app_landing_page')]
    public function index(CoachRepository $coachRepository, CategorieRepository $categorieRepository): Response
    {
        if ($this->getUser()){
            return $this->redirectToRoute('app_main');
        }
        else{
            return $this->render('landing_page/index.html.twig');
        }
    }
}
