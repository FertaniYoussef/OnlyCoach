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
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Stripe;

class AbonnementController extends AbstractController
{
    #[Route('/abonnement', name: 'app_abonnement')]
    public function index(): Response
    {
        return $this->render('abonnement/index.html.twig', [
            'stripe_key' => $_ENV["STRIPE_KEY"],
        ]);
    }

  #[Route('/abonnement/{coachId}/checkout/success', name:'success')]
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
        }  */
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
          
          $this->addFlash(
            'success',
            'Payment Succesful'
        );
            return $this->render('abonnement/success.html.twig', [
                'stripe_key' => $_ENV["STRIPE_KEY"],
                'coach'=> $coach
            ]); 
    }
    #[Route('/cancel/{subscriptionid}',name:'unsubscribe_from_coach')]
        public function unsubscribeToCoach(Request $request, $subscriptionid,AbonnementRepository $aborepo,ManagerRegistry $doctrine)
        {
           
            $user = $this->getUser();
            $abonnement= $aborepo->find($subscriptionid);
            if (!$abonnement) {
                throw $this->createNotFoundException('The subscription does not exist');
            }        
            $user->removeIdAbonnement($abonnement);
            $em = $doctrine->getManager();
            $em->remove($abonnement);
            $em->flush();
          
        }
        #[Route('/abonnement/{coachId}/checkout/failed', name: 'failure')]
        public function failedPayment(): Response
        {
            return $this->render('main/index.html.twig', [
                'stripe_key' => $_ENV["STRIPE_KEY"],
            ]);
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
        return $this->render('abonnement/index.html.twig', [
            'stripe_key' => $_ENV["STRIPE_KEY"],
            'CLIENT_SECRET'=>$_ENV['STRIPE_SECRET'],
            'coach'=> $coach
        ]); 
    }
  
    #[Route('/abonnement/{coachId}/checkout',name:'subscribe_checkout',methods:['POST'])]
    public function checkout(Request $request, $coachId,CoachRepository $coachrepo) : Response
    {
        $coach =$coachrepo->find($coachId);
        if (!$coach) {
            throw $this->createNotFoundException('The coach does not exist');
        }
$total= $coach->getPrix() * 1.1 ;
$formattedPrice = number_format($total, 2, '.', '');
        Stripe\Stripe::setApiKey($_ENV["STRIPE_SECRET"]);
        try {
            Stripe\Charge::create ([
                "amount" => $formattedPrice * 100,
                "currency" => "usd",
                "source" => $request->request->get('stripeToken'),
                "description" => " Payment Test"
            ]);

            // Paiement réussi, on redirige l'utilisateur vers la page de succès
        
         
            return $this->redirectToRoute('success', ["coachId"=>$coachId], Response::HTTP_SEE_OTHER);
        } catch (Stripe\Exception\CardException $e) {
            // Paiement échoué, on redirige l'utilisateur vers la page d'échec
            $this->addFlash(
                'failure',
                'Payment Succesful'
            );
            return $this->redirectToRoute('faillure', ["coachId"=>$coachId], Response::HTTP_SEE_OTHER);
        }
          

    }
}
