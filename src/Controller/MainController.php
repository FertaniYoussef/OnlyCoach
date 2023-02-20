<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use App\Entity\Coach;
use App\Repository\CoachRepository;
use App\Repository\CoursRepository;

class MainController extends AbstractController
{
    #[Route('/main', name: 'app_main')]
    public function index(CoachRepository $coachRepository, CategorieRepository $categorieRepository, CoursRepository $coursRepo): Response
    {
        /* Cours fetching */

        $cours = $coursRepo->FindBy(array(), array('nbVues' => 'DESC'), 3, 0);

        /* fin cours fetching */
        if ($this->getUser()){
            return $this->render('main/index.html.twig', array('popular' => $cours,  'coaches' => $coachRepository->findAll(), 'categories' => $categorieRepository->findAll()));    
        }
        else{
            return $this->redirectToRoute("app_login");
        }
    }

}
