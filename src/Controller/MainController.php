<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use App\Entity\Coach;
use App\Repository\CoachRepository;

class MainController extends AbstractController
{
    #[Route('/main', name: 'app_main')]
    public function index(CoachRepository $coachRepository, CategorieRepository $categorieRepository): Response
    {
        $user = $this->getUser();
        if ($this->getUser()){
            if($user->getRoles()[0]=="ROLE_USER"){
                return $this->render('main/index.html.twig', array('popular' => [['id' => '1', 'title' => 'Get started with Stretching. - Learn the basics in less than 24 Hours!', 'creator' => 'Amrou Ghribi', 'background' => 'StretchingImage.jpg', 'rating' => 4.3, 'totalratings' => 1098],['id' => '2', 'title' => 'Get started with Yoga. - Learn the basics in less than 24 Hours!', 'creator' => 'Aziz Rezgui', 'background' => 'YogaImage.jpg', 'rating' => 3.7, 'totalratings' => 6782],['id' => '3', 'title' => 'Get started with Resistance. - Learn the basics in less than 24 Hours!', 'creator' => 'Fatma Masmoudi', 'background' => 'ResistanceImage.jpg', 'rating' => 3.2, 'totalratings' => 4]],  'coaches' => $coachRepository->findAll(), 'categories' => $categorieRepository->findAll(),'userinfo'=>$this->getUser()));    
            }elseif($user->getRoles()[0]=="ROLE_COACH"){
                return $this->render('dashboard/coach/index.html.twig', [
                    'controller_name' => 'DashboardController','userinfo'=>$this->getUser()
                ]);
            }elseif($user->getRoles()[0]=="ROLE_ADMIN"){
                {return $this->render('dashboard/admin/index.html.twig',array('userinfo'=>$this->getUser()));}
            }
        }
        else{
            return $this->redirectToRoute("app_login");
        }
    }

}