<?php

namespace App\Controller;
use App\Entity\Reponse;
use App\Form\ReponseType;
use App\Repository\FeedbackRepository;
use App\Repository\ReponseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;

class ReponseController extends AbstractController
{
    #[Route('/reponse', name: 'app_reponse')]
    public function index(): Response
    {

        return $this->render('reponse/index.html.twig', [
            'controller_name' => 'ReponseController',
        ]);
    }

    #[Route('/admin/dashboard/feedback/consulter/addReponse/{id}', name: 'addreponse')]
    public function addreponse(Request $request,EntityManagerInterface $EM): Response
    {

        $reponse=new Reponse();
        $form=$this->createForm(ReponseType::class,$reponse);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
         //   $Commentaire->setDate(new DateTime());
            $EM->persist($reponse);
            $EM->flush();

            return $this->redirectToRoute('app_dashboard_adminFeedbacks');
        }
        else
            return $this->render('dashboard/admin/feedback/consulterFeedback.html.twig',['f'=>$form->createView()]);

    }
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }
    #[Route('/contact/show', name: 'afficher')]
    public function afficher(Request $request,RequestStack $requestStack,ReponseRepository $repository): Response
    {

       $reponses=$repository->findAll();

        return $this->render('contact/show.html.twig', [
            'reponses'=>$reponses,
            'url_retour' => $this->requestStack->getMainRequest()->headers->get('referer'),
        ]);
    }

}
