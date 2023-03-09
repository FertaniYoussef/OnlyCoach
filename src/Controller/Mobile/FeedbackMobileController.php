<?php

namespace App\Controller\Mobile;

use App\Entity\Feedback;
use App\Repository\FeedbackRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/mobile/feedback")
 */
class FeedbackMobileController extends AbstractController
{
    /**
     * @Route("", methods={"GET"})
     */
    public function index(FeedbackRepository $feedbackRepository): Response
    {
        $feedbacks = $feedbackRepository->findAll();

        if ($feedbacks) {
            return new JsonResponse($feedbacks, 200);
        } else {
            return new JsonResponse([], 204);
        }
    }

    /**
     * @Route("/add", methods={"POST"})
     */
    public function add(Request $request, UserRepository $userRepository, ManagerRegistry $doctrine): JsonResponse
    {
        $feedback = new Feedback();

        return $this->manage($feedback, $userRepository, $request, false, $doctrine);
    }

    /**
     * @Route("/edit", methods={"POST"})
     */
    public function edit(Request $request, FeedbackRepository $feedbackRepository, UserRepository $userRepository, ManagerRegistry $doctrine): Response
    {
        $feedback = $feedbackRepository->find((int)$request->get("id"));

        if (!$feedback) {
            return new JsonResponse(null, 404);
        }

        return $this->manage($feedback, $userRepository, $request, true, $doctrine);
    }

    public function manage($feedback, $userRepository, $request, $isEdit,$doctrine): JsonResponse
    {
        $user = $userRepository->find((int)$request->get("user"));
        if (!$user) {
            return new JsonResponse("user with id " . (int)$request->get("user") . " does not exist", 203);
        }


        $feedback->constructor(
            $user,
            $request->get("sujet"),
            $request->get("email"),
            $request->get("description"),
            DateTime::createFromFormat("d-m-Y", $request->get("dateFeedback")),
            (int)$request->get("status")
        );


        $entityManager = $doctrine->getManager();
        $entityManager->persist($feedback);
        $entityManager->flush();

        return new JsonResponse($feedback, 200);
    }

    /**
     * @Route("/delete", methods={"POST"})
     */
    public function delete(Request $request, EntityManagerInterface $entityManager, FeedbackRepository $feedbackRepository): JsonResponse
    {
        $feedback = $feedbackRepository->find((int)$request->get("id"));

        if (!$feedback) {
            return new JsonResponse(null, 200);
        }

        $entityManager->remove($feedback);
        $entityManager->flush();

        return new JsonResponse([], 200);
    }

    /**
     * @Route("/deleteAll", methods={"POST"})
     */
    public function deleteAll(EntityManagerInterface $entityManager, FeedbackRepository $feedbackRepository): Response
    {
        $feedbacks = $feedbackRepository->findAll();

        foreach ($feedbacks as $feedback) {
            $entityManager->remove($feedback);
            $entityManager->flush();
        }

        return new JsonResponse([], 200);
    }

}
