<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\OffreRepository;
use Doctrine\ORM\Mapping as ORM;

 use App\Entity\Offre;
 use Symfony\Component\HttpFoundation\JsonResponse;
 use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
 use Symfony\Component\Serializer\Serializer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Validator\Constraints\Json;
use Doctrine\ORM\EntityManagerInterface as ORMEntityManagerInterface;

class OffreController extends AbstractController
{
    #[Route('/offre', name: 'app_offre')]
    public function index(): Response
    {
        
        return $this->render('offre/index.html.twig', [
            'controller_name' => 'OffreController',
        ]);
    }


    
    




    /******************Ajouter Offre*****************************************/
     /**
      * @Route("/addOffre", name="add_reclamation")
      * @Method("POST")
      */

      public function ajouterOffre(Request $request,ManagerRegistry $doctrine)
      {
          $offre= new Offre();
          $nom = $request->query->get("nom");
          $em = $doctrine->getManager(); 
          $offre->setNom($nom);
          $date_Deb = $request->query->get("dateDeb");

          $offre->setDateDeb($date_Deb);
 
          $em->persist($offre);
          $em->flush();
          $serializer = new Serializer([new ObjectNormalizer()]);
          $formatted = $serializer->normalize($offre);
          return new JsonResponse($formatted);
 
      }

      


 /******************Supprimer Reclamation*****************************************/

     /**
      * @Route("/deleteOffre{id}", name="delete_reclamation")
      * @Method("DELETE")
      */

      public function deleteOffre(Request $request,ManagerRegistry $doctrine) {
        $id = $request->get("id");

        $page = $request->query->getInt('page', 1); 
         $offre = $request->get("offre");

         $em = $doctrine->getManager();
         $offre = $em->getRepository(Offre::class)->find($id);
         if($offre!=null ) {
             $em->remove($offre);
             $em->flush();

             $serialize = new Serializer([new ObjectNormalizer()]);
             $formatted = $serialize->normalize("Reclamation a ete supprimee avec success.");
             return new JsonResponse($formatted);

         }
         return new JsonResponse("offre");


     }



    /******************affichage Reclamation*****************************************/

     /**
      * @Route("/displayOffre", name="display_reclamation")
      */
      public function allRecAction(ORMEntityManagerInterface $em)
      {
 
          $offre = $em->getRepository(Offre::class)->findAll();
          $serializer = new Serializer([new ObjectNormalizer()]);
          $formatted = $serializer->normalize($offre);
 
          return new JsonResponse($formatted);
 
      }
  
}
