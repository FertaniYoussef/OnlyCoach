<?php

namespace App\Controller;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Symfony\Component\Serializer\SerializerInterface;



#[Route('/admin/dashboard/categorie')]
class CategorieDashboardController extends AbstractController
{
    #[Route('/', name: 'app_categorie_dashboard_index', methods: ['GET' ,'POST'])]
    public function index(CategorieRepository $categorieRepository, ManagerRegistry $doctrine,Request $request): Response
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request) ;
        if ($form->isSubmitted() && $form->isValid()) { 
            $em= $doctrine->getManager();
            $em->persist($categorie);
            $em->flush();
            return $this->redirectToRoute('app_categorie_dashboard_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('categorie_dashboard/index.html.twig', [
            'categories' => $categorieRepository->findAll(),
            'form' => $form->createView()
        ]);
    }


    #[Route('/api', name: 'app_categorie_dashboard_api')]
public function apiCategorie(CategorieRepository $repository): JsonResponse
{
    $categories = $repository->createQueryBuilder('c')
        ->select('c.id, c.Type')
        ->getQuery()
        ->getResult();

    $catArray = [];
    foreach ($categories as $categorie) {
        $catArray[] = [
            'id' => $categorie['id'],
            'type' => $categorie['Type'],
        ];
    }

    return new JsonResponse($catArray);
}
#[Route('/create', name: 'app_dashboard_api_add_categorie', methods: ['POST'])]
#[ParamConverter("categorie", class :"App\Entity\Categorie")]


public function create(Request $request,ManagerRegistry $doctrine): JsonResponse
{
    $entityManager = $doctrine->getManager();
    $data = json_decode($request->getContent(), true);
    if (is_null($data) || !isset($data['type']) || is_null($data['type'])) {
        return new JsonResponse(['message' => 'Invalid data'], Response::HTTP_BAD_REQUEST);
    }

    $categorie = new Categorie();
    $categorie->setType($data['type']);
    $entityManager->persist($categorie);
    $entityManager->flush();

    return new JsonResponse(['message' => 'Categorie created!'], Response::HTTP_CREATED);
}

#[Route('/{id}/update', name : 'update_categorie', methods: ["GET",])]
#[ParamConverter("categorie", class :"App\Entity\Categorie")]

public function update(Request $request, Categorie $categorie, EntityManagerInterface $entityManager): JsonResponse
{
    $data = json_decode($request->getContent(), true);
    if (is_null($data) || !isset($data['type'])) {
        return new JsonResponse(['message' => 'Invalid data'], Response::HTTP_BAD_REQUEST);
    }

    $categorie->setType($data['type']);

    $entityManager->persist($categorie);
    $entityManager->flush();

    return new JsonResponse(['message' => 'Categorie updated!'], Response::HTTP_OK);
}

#[Route('/{id}/deleteapi/', name: 'app_dashboard_api_delete_categorie')]

public function apidelete(int $id,ManagerRegistry $doctrine): JsonResponse
{
    $entityManager = $doctrine->getManager();
    $categorie = $entityManager->getRepository(Categorie::class)->find($id);

    if (!$categorie) {
        return new JsonResponse(['error' => 'Categorie not found.'], Response::HTTP_NOT_FOUND);
    }

    $entityManager->remove($categorie);
    $entityManager->flush();

    return new JsonResponse(['message' => 'Categorie deleted!']);
}




    #[Route('/{id}/edit', name: 'app_categorie_dashboard_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Categorie $categorie, CategorieRepository $categorieRepository): Response
    {
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);
        

        if ($form->isSubmitted() && $form->isValid()) {
            $categorieRepository->save($categorie, true);

            return $this->redirectToRoute('app_categorie_dashboard_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('categorie_dashboard/edit.html.twig', [
            'categorie' => $categorie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_categorie_dashboard_delete', methods: ['POST' ,'GET'])]
    public function delete(Request $request, Categorie $categorie, CategorieRepository $categorieRepository ,$id, ManagerRegistry $doctrine): Response
    { /* 
        if ($this->isCsrfTokenValid('delete'.$categorie->getId(), $request->request->get('_token'))) {
            $categorieRepository->remove($categorie, true);
        }

        return $this->redirectToRoute('app_categorie_dashboard_index', [], Response::HTTP_SEE_OTHER);
        */
        $categorie= $categorieRepository->find($id);
        $em = $doctrine->getManager();
        $em->remove($categorie);
        $em->flush();
        return  $this->redirectToRoute('app_categorie_dashboard_index');
    }
}
    
