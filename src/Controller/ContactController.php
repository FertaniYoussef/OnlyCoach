<?php

namespace App\Controller;
use App\Entity\Feedback;
use App\Form\FeedbackType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(): Response
    {
        $feedback = new Feedback();
        $form = $this->createForm(FeedbackType::class,$feedback);

        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
            'form'=> $form->createView()
        ]);
    }
    #[Route('/contact/ajout', name: 'app_feedback_ajout')]
    public function ajout_feedback_coach(Request $request,ManagerRegistry $doctrine): Response
    {
       // $user = new User();
        $user=$this->getUser();
        $Feedback=new Feedback();
        $form=$this->createForm(FeedbackType::class,$Feedback);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {

            $sujet=$request->request->get('Sujet');
            $description=$request->request->get('description');
            $Feedback->setUser($user);
           // $mail=$user->getEmail();
            $em= $doctrine->getManager();
           // $Feedback->setUser($mail);
            $Feedback->setDateFeedback(new \DateTime("now"));
            $Feedback->setSujet($sujet);
            $Feedback->setStatus(0);
            $Feedback->setDescription($description);

            $em->persist($Feedback);
            $em->flush();
            return $this->render('landing_page/index.html.twig');
        }

        return $this->render('landing_page/index.html.twig');
    }
}
