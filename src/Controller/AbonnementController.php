<?php

namespace App\Controller;
use App\Entity\Coach;
use App\Entity\User;
use App\Entity\Abonnement;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;
use App\Repository\CoachRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
    public function subscribeToCoach(Request $request, $coachId,UserRepository $userrepo,CoachRepository $coachrepo)
    {
        $user = $this->getUser();
        $coach =$coachrepo->find($coachId);
        if (!$coach) {
            throw $this->createNotFoundException('The coach does not exist');
        }
        // Check if the user is already subscribed to the coach
        if ($user->isSubscribedTo($coach)) {
            throw $this->createNotFoundException('The coach exist');
        }
        $subscription = new Abonnement();
        $subscription->setDateDeb(new \DateTime());
        $endDate = (new \DateTime())->modify('+30 days');

        $subscription->setDateFin($endDate);
        $subscription->setUser($user);
        $subscription->setCoach($coach);
          // Save the subscription to the database
          $entityManager = $this->getDoctrine()->getManager();
          $entityManager->persist($subscription);
          $entityManager->flush();
            // Redirect the user to a page confirming the subscription
        return $this->redirectToRoute('subscription_confirmation');
    }
    public function subscriptionConfirmation(): Response
    {
        // Render a template confirming the subscription
        return $this->render('abonnement/confirmation.html.twig');
    }
}
