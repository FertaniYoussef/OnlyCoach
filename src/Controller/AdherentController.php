<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\CoursRepository;
use App\Entity\User;
use App\Entity\Adherents;
use App\Entity\Abonnement;
use App\Repository\UserRepository;
use App\Repository\AdherentsRepository;
class AdherentController extends AbstractController
{
    #[Route('/adherent', name: 'app_adherent')]
    public function index(): Response
    {
        return $this->render('adherent/index.html.twig', [
            'controller_name' => 'AdherentController',
        ]);
    }

    #[Route('/joincourse/{courseId}' , name : 'course_join')]
    public function joinCours(Request $request ,$courseId,CoursRepository $courserep, UserRepository $userrep,ManagerRegistry $doctrine): Response
    {
        $user= $this->getUser();
        $cours= $courserep -> find($courseId);
        if (!$cours) {
            throw $this->createNotFoundException('The course does not exist');
        }
        $userid= $user->getId();
        $adherent = new Adherents();
        $adherent -> setDate(new \DateTime());
        $adherent -> setUser($userid);
        $adherent -> setCours($courseId);


        $entityManager = $doctrine->getManager();
        $entityManager->persist($adherent);
        $entityManager->flush();
        die();
        }

        #[Route('/leavecourse/{courseId}' , name : 'leave_join')]
        public function leaveCours(Request $request ,$courseId,CoursRepository $courserep, UserRepository $userrep,AdherentsRepository $adrep,ManagerRegistry $doctrine): Response
        {
            $user= $this->getUser();
            $cours= $courserep -> find($courseId);
            if (!$cours) {
                throw $this->createNotFoundException('The course does not exist');
            }
            $userid= $user->getId();
            $adherent= $adrep->findOneBy(['user'=>$userid,'cours'=>$courseId]);

            $em = $doctrine->getManager();
            $em->remove($adherent);
            $em->flush();
            die();
        }
    
        
       
}
