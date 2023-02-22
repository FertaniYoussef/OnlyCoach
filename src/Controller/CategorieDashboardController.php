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
use Symfony\Component\Routing\Annotation\Route;


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
    
