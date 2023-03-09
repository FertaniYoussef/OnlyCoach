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
        $user = $this->getUser();
        /* Cours fetching */

        $cours = $coursRepo->FindBy(array(), array('nbVues' => 'DESC'), 3, 0);

        /* fin cours fetching */
        if ($this->getUser()){
            if($user->getRoles()[0]=="ROLE_USER"){
                return $this->render('main/index.html.twig', array('popular' => $cours,  'coaches' => $coachRepository->findAll(), 'categories' => $categorieRepository->findAll(),'userinfo'=>$this->getUser()));    
            }elseif($user->getRoles()[0]=="ROLE_COACH"){
                return $this->redirectToRoute('app_dashboard');
            }elseif($user->getRoles()[0]=="ROLE_ADMIN"){
                {return $this->redirectToRoute("app_dashboard_adminIndex");}
            }
        }
        else{
            return $this->redirectToRoute("app_login");
        }
    }

}