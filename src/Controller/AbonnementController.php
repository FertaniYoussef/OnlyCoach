<?php

namespace App\Controller;
use App\Entity\Coach;
use App\Entity\User;
use App\Entity\Abonnement;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;
use App\Repository\CoachRepository;
use App\Repository\CategorieRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class AbonnementController extends AbstractController
{
    #[Route('/abonnement', name: 'app_abonnement')]
    public function index(): Response
    {
        return $this->render('abonnement/index.html.twig', [
            'controller_name' => 'AbonnementController',
        ]);
    }

    #[Route('/abonnement/{coachId}', name:'subscribe_to_coach')]
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
        } */
        $subscription = new Abonnement();
        $subscription->setDateDeb(new \DateTime());
        $endDate = (new \DateTime())->modify('+30 days');

        $subscription->setDateFin($endDate);
        $subscription->setUser($user);
        $subscription->setCoach($coach);
        $user-> addIdAbonnement($subscription);
      
          // Save the subscription to the database
          $entityManager = $doctrine->getManager();
          $entityManager->persist($subscription);
          $entityManager->flush();
          
            // Redirect the user to a page confirming the subscription
            return $this->render('main/index.html.twig', array('popular' => [['id' => '1', 'title' => 'Get started with Stretching. - Learn the basics in less than 24 Hours!', 'creator' => 'Amrou Ghribi', 'background' => 'StretchingImage.jpg', 'rating' => 4.3, 'totalratings' => 1098],['id' => '2', 'title' => 'Get started with Yoga. - Learn the basics in less than 24 Hours!', 'creator' => 'Aziz Rezgui', 'background' => 'YogaImage.jpg', 'rating' => 3.7, 'totalratings' => 6782],['id' => '3', 'title' => 'Get started with Resistance. - Learn the basics in less than 24 Hours!', 'creator' => 'Fatma Masmoudi', 'background' => 'ResistanceImage.jpg', 'rating' => 3.2, 'totalratings' => 4]],  'coaches' => $coachrepo->findAll(), 'categories' => $catrepo->findAll()));  
    }
    public function subscriptionConfirmation(): Response
    {
        // Render a template confirming the subscription
        return $this->render('abonnement/confirmation.html.twig');
    }
}
