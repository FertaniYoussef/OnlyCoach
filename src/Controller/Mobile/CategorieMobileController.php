<?php

namespace App\Controller\Mobile;

use App\Entity\Categorie;
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
 * @Route("/mobile/categorie")
 */
class CategorieMobileController extends AbstractController
{
    /**
     * @Route("", methods={"GET"})
     */
    public function index(CategorieRepository $categorieRepository): Response
    {
        $categories = $categorieRepository->findAll();

        if ($categories) {
            return new JsonResponse($categories, 200);
        } else {
            return new JsonResponse([], 204);
        }
    }

    /**
     * @Route("/add", methods={"POST"})
     */
    public function add(Request $request, ManagerRegistry $doctrine): JsonResponse
    {
        $categorie = new Categorie();


        $categorie->constructor(
            $request->get("type")
        );

        $entityManager = $doctrine->getManager();
        $entityManager->persist($categorie);
        $entityManager->flush();

        return new JsonResponse($categorie, 200);

        
    }

    /**
     * @Route("/edit", methods={"POST"})
     */
    public function edit(Request $request, CategorieRepository $categorieRepository, ManagerRegistry $doctrine): Response
    {
        $categorie = $categorieRepository->find((int)$request->get("id"));

        if (!$categorie) {
            return new JsonResponse(null, 404);
        }


        $categorie->constructor(
            $request->get("type")
        );

        $entityManager = $doctrine->getManager();
        $entityManager->persist($categorie);
        $entityManager->flush();

        return new JsonResponse($categorie, 200);
    }

    /**
     * @Route("/delete", methods={"POST"})
     */
    public function delete(Request $request, EntityManagerInterface $entityManager, CategorieRepository $categorieRepository): JsonResponse
    {
        $categorie = $categorieRepository->find((int)$request->get("id"));

        if (!$categorie) {
            return new JsonResponse(null, 200);
        }

        $entityManager->remove($categorie);
        $entityManager->flush();

        return new JsonResponse([], 200);
    }


}