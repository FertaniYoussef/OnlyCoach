<?php

namespace App\Controller\Mobile;

use App\Entity\Coach;
use App\Repository\CoachRepository;
use App\Repository\UserRepository;
use App\Repository\CategorieRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/mobile/coach")
 */
class CoachMobileController extends AbstractController
{
    /**
     * @Route("", methods={"GET"})
     */
    public function index(CoachRepository $coachRepository): Response
    {
        $coachs = $coachRepository->findAll();

        if ($coachs) {
            return new JsonResponse($coachs, 200);
        } else {
            return new JsonResponse([], 204);
        }
    }

    /**
     * @Route("/add", methods={"POST"})
     */
    public function add(ManagerRegistry $doctrine, Request $request, UserRepository $userRepository, CategorieRepository $categorieRepository): JsonResponse
    {
        $coach = new Coach();


        $user = $userRepository->find((int)$request->get("user"));
        if (!$user) {
            return new JsonResponse("user with id " . (int)$request->get("user") . " does not exist", 203);
        }

        $categorie = $categorieRepository->find((int)$request->get("categorie"));
        if (!$categorie) {
            return new JsonResponse("categorie with id " . (int)$request->get("categorie") . " does not exist", 203);
        }


        $file = $request->files->get("file");
        if ($file) {
            $imageFileName = md5(uniqid()) . '.' . $file->guessExtension();
            try {
                $file->move($this->getParameter('images_directory'), $imageFileName);
            } catch (FileException $e) {
                dd($e);
            }
        } else {
            if ($request->get("image")) {
                $imageFileName = $request->get("image");
            } else {
                $imageFileName = "null";
            }
        }

        $coach->constructor(
            $user,
            $categorie,
            $request->get("nom"),
            $request->get("prenom"),
            $imageFileName,
            $request->get("description"),
            (int)$request->get("prix"),
            (int)$request->get("rating")
        );

        $entityManager = $doctrine->getManager();
        $entityManager->persist($coach);
        $entityManager->flush();

        return new JsonResponse($coach, 200);


    }

    /**
     * @Route("/edit", methods={"POST"})
     */
    public function edit(Request         $request, CoachRepository $coachRepository, UserRepository $userRepository, CategorieRepository $categorieRepository,
                         ManagerRegistry $doctrine): Response
    {
        $coach = $coachRepository->find((int)$request->get("id"));

        if (!$coach) {
            return new JsonResponse(null, 404);
        }


        $user = $userRepository->find((int)$request->get("user"));
        if (!$user) {
            return new JsonResponse("user with id " . (int)$request->get("user") . " does not exist", 203);
        }

        $categorie = $categorieRepository->find((int)$request->get("categorie"));
        if (!$categorie) {
            return new JsonResponse("categorie with id " . (int)$request->get("categorie") . " does not exist", 203);
        }


        $file = $request->files->get("file");
        if ($file) {
            $imageFileName = md5(uniqid()) . '.' . $file->guessExtension();
            try {
                $file->move($this->getParameter('images_directory'), $imageFileName);
            } catch (FileException $e) {
                dd($e);
            }
        } else {
            if ($request->get("image")) {
                $imageFileName = $request->get("image");
            } else {
                $imageFileName = "null";
            }
        }

        $coach->constructor(
            $user,
            $categorie,
            $request->get("nom"),
            $request->get("prenom"),
            $imageFileName,
            $request->get("description"),
            (int)$request->get("prix"),
            (int)$request->get("rating")
        );


        $entityManager = $doctrine->getManager();
        $entityManager->persist($coach);
        $entityManager->flush();

        return new JsonResponse($coach, 200);
    }

    /**
     * @Route("/delete", methods={"POST"})
     */
    public function delete(Request $request, EntityManagerInterface $entityManager, CoachRepository $coachRepository): JsonResponse
    {
        $coach = $coachRepository->find((int)$request->get("id"));

        if (!$coach) {
            return new JsonResponse(null, 200);
        }

        $entityManager->remove($coach);
        $entityManager->flush();

        return new JsonResponse([], 200);
    }


    /**
     * @Route("/image/{image}", methods={"GET"})
     */
    public function getPicture(Request $request): BinaryFileResponse
    {
        return new BinaryFileResponse(
            $this->getParameter('images_directory') . "/" . $request->get("image")
        );
    }

}
