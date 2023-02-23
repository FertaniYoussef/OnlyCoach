<?php

namespace App\Controller;
use App\Entity\Coach;
use App\Entity\User;
use App\Entity\Abonnement;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;
use App\Repository\AbonnementRepository;
use App\Repository\CoachRepository;
use App\Repository\CategorieRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Stripe\Stripe;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Stripe\Checkout\Session;

class AbonnementController extends AbstractController
{
    #[Route('/abonnement', name: 'app_abonnement')]
    public function index(): Response
    {
        return $this->render('abonnement/index.html.twig', [
            'controller_name' => 'AbonnementController',
        ]);
    }

/*     #[Route('/abonnement/{coachId}', name:'subscribe_to_coach')]
    public function subscribeToCoach(Request $request, $coachId,UserRepository $userrepo,CoachRepository $coachrepo,CategorieRepository $catrepo,ManagerRegistry $doctrine)
    {
        $user = $this->getUser();
      $coach =$coachrepo->find($coachId);
        if (!$coach) {
            throw $this->createNotFoundException('The coach does not exist');
        }
        // Check if the user is already subscribed to the coach
       /*  if ($user->isSubscribedTo($coach)) {
            throw $this->createNotFoundException('The coach exist');
        } 
        $subscription = new Abonnement();
        $subscription->setDateDeb(new \DateTime());
        $endDate = (new \DateTime())->modify('+30 days');

        $subscription->setDateFin($endDate);
        $subscription->setUser($user);
        $subscription->setCoach($coach);
        $user-> addIdAbonnement($subscription);
      
         
          $entityManager = $doctrine->getManager();
          $entityManager->persist($subscription);
          $entityManager->flush();
          
          
            return $this->render('abonnement/index.html.twig'); 
    }*/
    #[Route('/cancel/{subscriptionid}',name:'unsubscribe_from_coach')]
        public function unsubscribeToCoach(Request $request, $subscriptionid,AbonnementRepository $aborepo,ManagerRegistry $doctrine)
        {
           
            $user = $this->getUser();
            $abonnement= $aborepo->find($subscriptionid);
            if (!$abonnement) {
                throw $this->createNotFoundException('The subscription does not exist');
            }        
            
            $em = $doctrine->getManager();
            $em->remove($abonnement);
            $em->flush();
            die();
        }

     #[Route('/abonnement/{coachId}',name:'subscribe_coach')]
    public function subscriptionConfirmation(Request $request, $coachId,UserRepository $userrepo,CoachRepository $coachrepo,CategorieRepository $catrepo,ManagerRegistry $doctrine): Response
    {
        $user = $this->getUser();
        $coach =$coachrepo->find($coachId);
          if (!$coach) {
              throw $this->createNotFoundException('The coach does not exist');
          }
        // Render a template confirming the subscription
        return $this->render('abonnement/index.html.twig',array('coach'=>$coach));
    }
    #[Route('/abonnement/{coachId}/checkout',name:'subscribe_checkout')]
    public function checkout(Request $request, $coachId,CoachRepository $coachrepo) : Response
    {
        $coach =$coachrepo->find($coachId);
        if (!$coach) {
            throw $this->createNotFoundException('The coach does not exist');
        }

        Stripe::setApiKey('sk_test_51MeLAvEYkEbKW1v6I2M4WnJtf1Ts5kfNlJr3q2XnaUxJ2MwGbYhjq6tOxutQvAyBXqUYXmjm6DaHAPDw1c1JuyS300btIzyOVi');
         $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'usd',
                        'unit_amount' => $coach->getPrix() * 100, // Stripe uses cents as the currency unit
                        'product_data' => [
                            'name' => $coach->getNom(),
                            'description' => $coach->getDescription(),
                        ],
                    ],
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
            'success_url' => $this->generateUrl('subscription_checkout_success', [], true),
            'cancel_url' => $this->generateUrl('subscription_checkout_cancel', [], true),
        ]);
          

    }
}
