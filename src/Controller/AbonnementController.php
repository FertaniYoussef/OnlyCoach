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
use App\Repository\CoursRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Stripe;
use Symfony\Component\Serializer\SerializerInterface ;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

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
    public function subscribeToCoach(Request $request, $coachId,UserRepository $userrepo,CoachRepository $coachrepo,AbonnementRepository $abbrepo,ManagerRegistry $doctrine)
    {
        $user = $this->getUser();
      $coach =$coachrepo->find($coachId);
        if (!$coach) {
            throw $this->createNotFoundException('The coach does not exist');
        }
        $subscription = new Abonnement();
        $subscription->setDateDeb(new \DateTime());
        $endDate = (new \DateTime())->modify('+30 days');

        $subscription->setDateFin($endDate);
        $subscription->setUser($user);
        $subscription->setCoach($coach);
        $subscription->setPrix ($coach -> getPrix() * 1.1);
        $user-> addIdAbonnement($subscription);
        $coach->addIdAbonnement($subscription);


          $entityManager = $doctrine->getManager();
          $entityManager->persist($subscription);
          $entityManager->flush();

          $this->addFlash(
            'success',
            'Payment Succesful'
        );
            return $this->render('abonnement/success.html.twig', [
                'stripe_key' => $_ENV["STRIPE_KEY"],
                'coach'=> $coach,
            ]);
    }
    #[Route('/cancel/{subscriptionid}',name:'unsubscribe_from_coach')]
        public function unsubscribeToCoach(Request $request, $subscriptionid,AbonnementRepository $aborepo,ManagerRegistry $doctrine,CategorieRepository $categorieRepository, CoursRepository $coursRepo,CoachRepository $coachRepository)
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
            $cours = $coursRepo->FindBy(array(), array('nbVues' => 'DESC'), 3, 0);

        /* fin cours fetching */
        if ($this->getUser()){
            return $this->render('main/index.html.twig', array('popular' => $cours,  'coaches' => $coachRepository->findAll(), 'categories' => $categorieRepository->findAll()));
        }
        else{

            return $this->redirectToRoute("app_login");
        }
        }
        #[Route('/abonnement/{coachId}/checkout/failed', name: 'failure')]
        public function failedPayment(Request $request, $coachId, CoachRepository $coachrepo): Response
        {
            $user = $this->getUser();
            $coach =$coachrepo->find($coachId);
              if (!$coach) {
                  throw $this->createNotFoundException('The coach does not exist');
              }
            return $this->render('abonnement/failed.html.twig', [
                'stripe_key' => $_ENV["STRIPE_KEY"],
                'coach'=>$coach
            ]);
        }
        #[Route('/abonnement/{coachId}/failed',name:'failed_exist_already')]
        public function subscriptionExist(): Response
        {
            return $this->render('abonnement/already.html.twig'
            );
        }
     #[Route('/abonnement/{coachId}',name:'subscribe_coach')]
    public function subscriptionConfirmation(Request $request, $coachId,UserRepository $userrepo,CoachRepository $coachrepo,AbonnementRepository $aborepo,ManagerRegistry $doctrine): Response
    {
        $user = $this->getUser();
        $coach =$coachrepo->find($coachId);
          if (!$coach) {
              throw $this->createNotFoundException('The coach does not exist');
          }
         $user_id= $user->getId();

          $isSubscribed= $aborepo->findOneBy(['user' => $user_id]);

          if ($isSubscribed) {
             return $this->redirectToRoute('failed_exist_already', ["coachId"=>$coachId], Response::HTTP_SEE_OTHER);
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
            return $this->redirectToRoute('failure', ["coachId"=>$coachId], Response::HTTP_SEE_OTHER);
        }


    }
    // Mobile API for web service

    #[Route('api/abonnement/{coachId}',name:'subscribe_api_coach',methods:['GET'])]
    public function subscriptionConfirmationJson(Request $request,SerializerInterface $serializer, $coachId,UserRepository $userrepo,CoachRepository $coachrepo,AbonnementRepository $aborepo,ManagerRegistry $doctrine): Response
    {
        {

            $user = $this->getUser();
            $coach =$coachrepo->find($coachId);
              if (!$coach) {
                  throw $this->createNotFoundException('The coach does not exist');
              }





             $response = [
                'id'=> $coach->getId(),
                'nom'=> $coach->getNom(),
                'prenom'=> $coach->getPrenom(),
                'description'=> $coach->getDescription(),
                'prix'=> $coach->getPrix(),
                'picture'=> $coach->getPicture()
              ];




        return $this->json($response);
    }
}
#[Route('api/abonnement/{coachId}/checkout',name:'subscribe_api_checkout')]
public function checkoutapi(Request $request, $coachId,CoachRepository $coachrepo) : Response
{


        // Paiement réussi, on redirige l'utilisateur vers la page de succès



        return $this->redirectToRoute('success_api', ["coachId"=>$coachId], Response::HTTP_SEE_OTHER);

    }



#[Route('api/abonnement/{coachId}/checkout/success', name:'success_api')]
public function subscribeToCoach_api(Request $request, $coachId,UserRepository $userrepo,CoachRepository $coachrepo,AbonnementRepository $abbrepo,ManagerRegistry $doctrine)
{

    $user = $userrepo->find(10);
  $coach =$coachrepo->find($coachId);
    if (!$coach) {
        return $this->json('Coach not found',404,[]);
    }

    $subscription = new Abonnement();
    $subscription->setDateDeb(new \DateTime());
    $endDate = (new \DateTime())->modify('+30 days');

    $subscription->setDateFin($endDate);
    $subscription->setUser($user);
    $subscription->setCoach($coach);
    $subscription->setPrix ($coach -> getPrix() * 1.1);




      $entityManager = $doctrine->getManager();
      $entityManager->persist($subscription);
      $entityManager->flush();



        return $this->json("okay",200,[]);

}
#[Route('api/abonnement/{coachId}/checkout/failed', name: 'failure_api')]
public function failedPayment_api(Request $request, $coachId, CoachRepository $coachrepo): Response
{
    $user = $this->getUser();
    $coach =$coachrepo->find($coachId);
      if (!$coach) {
          return $this->json("Coach not found",404,[]);
      }
      $response = [
        'stripe_key'=> $_ENV["STRIPE_KEY"],
        'id'=> $coach->getId(),
        'nom'=> $coach->getNom(),
        'prenom'=> $coach->getPrenom(),
        'description'=> $coach->getDescription(),
        'prix'=> $coach->getPrix(),
        'picture'=> $coach->getPicture()
      ];


        return $this->json($response,200,[]);

}
#[Route('api/cancel/{subscriptionid}',name:'unsubscribe_from_coach_api')]
public function unsubscribeToCoach_api(Request $request, $subscriptionid,AbonnementRepository $aborepo,ManagerRegistry $doctrine,CategorieRepository $categorieRepository, CoursRepository $coursRepo,CoachRepository $coachRepository)
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

    return $this->json("Subscription canceled",200,[]);

}
}
