<?php

namespace App\Controller;


use App\Entity\Commentaire;
use App\Form\CommentaireType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

class CommentaireController extends AbstractController
{
    /**
     * @Route("/Commentaire", name="app_Commentaire")
     */
    public function index(): Response
    {
        return $this->render('commentaire/index.html.twig', [
            'controller_name' => 'CommentaireController',
        ]);
    }



    /**
     * @Route("/afficherCommentaire", name="displayCommentaire")
     */
    public function afficherdecht(Request $request, EntityManagerInterface $EM): Response
    {
        $Commentaires = $EM->getRepository(Commentaire::class)->findAll();

        return $this->render('commentaire/index.html.twig', [
            'b' => $Commentaires
        ]);
    }



    /**
     * @Route("/addCommentaire", name="addCommentaire")
     */
    public function addCommentaire(Request $request, EntityManagerInterface $EM): Response
    {

        $Commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $Commentaire);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $Commentaire->setDate(new DateTime());
            $EM->persist($Commentaire);
            $EM->flush();

            return $this->redirectToRoute('displayCommentaire');
        } else
            return $this->render('commentaire/createCommentaire.html.twig', ['f' => $form->createView()]);
    }







    /**
     * @Route("/modifierCommentaire/{id}", name="modifierCommentaire")
     */
    public function modifierCommentaire(Request $request, $id, EntityManagerInterface $EM): Response
    {

        $Commentaires = $EM->getRepository(Commentaire::class)->find($id);
        $form = $this->createForm(CommentaireType::class, $Commentaires);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {



            $EM->flush();

            return $this->redirectToRoute('displayCommentaire');
        } else
            return $this->render('commentaire/modifierCommentaire.html.twig', ['f' => $form->createView()]);
    }



    /**
     * @Route("/deleteCommentaire", name="deleteCommentaire")
     */
    public function deleteCommentaire(Request $request, EntityManagerInterface $EM)
    {

        $Commentaire = $EM->getRepository(Commentaire::class)->findOneBy(array('id' => $request->query->get("id")));
        $EM->remove($Commentaire);
        $EM->flush();
        return new Response("success");
    }
}
