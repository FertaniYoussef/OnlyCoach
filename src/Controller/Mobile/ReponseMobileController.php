<?php

namespace App\Controller\Mobile;

use App\Entity\Reponse;
use App\Repository\FeedbackRepository;
use App\Repository\ReponseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/mobile/reponse")
 */
class ReponseMobileController extends AbstractController
{
    /**
     * @Route("", methods={"GET"})
     */
    public function index(ReponseRepository $reponseRepository): Response
    {
        $reponses = $reponseRepository->findAll();

        if ($reponses) {
            return new JsonResponse($reponses, 200);
        } else {
            return new JsonResponse([], 204);
        }
    }

    /**
     * @Route("/add", methods={"POST"})
     */
    public function add(Request $request, FeedbackRepository $feedbackRepository, ManagerRegistry $doctrine): JsonResponse
    {
        $reponse = new Reponse();

        return $this->manage($reponse, $feedbackRepository, $request, false,$doctrine);
    }

    /**
     * @Route("/edit", methods={"POST"})
     */
    public function edit(Request $request, ReponseRepository $reponseRepository, FeedbackRepository $feedbackRepository, ManagerRegistry $doctrine): Response
    {
        $reponse = $reponseRepository->find((int)$request->get("id"));

        if (!$reponse) {
            return new JsonResponse(null, 404);
        }

        return $this->manage($reponse, $feedbackRepository, $request, true,$doctrine);
    }

    public function manage($reponse, $feedbackRepository, $request, $isEdit,$doctrine): JsonResponse
    {
        $feedback = $feedbackRepository->find((int)$request->get("feedback"));
        if (!$feedback) {
            return new JsonResponse("feedback with id " . (int)$request->get("feedback") . " does not exist", 203);
        }


        $reponse->constructor(
            $feedback,
            $request->get("texte")
        );


        $entityManager = $doctrine->getManager();
        $entityManager->persist($reponse);
        $entityManager->flush();

        return new JsonResponse($reponse, 200);
    }

    /**
     * @Route("/delete", methods={"POST"})
     */
    public function delete(Request $request, EntityManagerInterface $entityManager, ReponseRepository $reponseRepository): JsonResponse
    {
        $reponse = $reponseRepository->find((int)$request->get("id"));

        if (!$reponse) {
            return new JsonResponse(null, 200);
        }

        $entityManager->remove($reponse);
        $entityManager->flush();

        return new JsonResponse([], 200);
    }

    /**
     * @Route("/deleteAll", methods={"POST"})
     */
    public function deleteAll(EntityManagerInterface $entityManager, ReponseRepository $reponseRepository): Response
    {
        $reponses = $reponseRepository->findAll();

        foreach ($reponses as $reponse) {
            $entityManager->remove($reponse);
            $entityManager->flush();
        }

        return new JsonResponse([], 200);
    }

}
