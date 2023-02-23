<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Feedback;
use App\Repository\FeedbackRepository;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request,FeedbackRepository $rep, ManagerRegistry $doctrine): Response
    {
        $feedback = new Feedback();
        if ($request->getMethod() === 'POST') {
            $inputs = $request->request->all();
            dump($inputs);
            $feedback->setSujet($inputs['sujet']);
            $feedback->setDescription($inputs['message']);
            // set feedback email as the current user email
            $feedback->setEmail($this->getUser()->getEmail());
            $feedback->setUser($this->getUser());
            $feedback->setStatus(0);
            $feedback->setDateFeedback(new \DateTime());
            $em = $doctrine->getManager();
            $em->persist($feedback);
            $em->flush();
            return $this->redirectToRoute('app_main');
        }
        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
        ]);
    }
}
