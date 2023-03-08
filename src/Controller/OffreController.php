<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
<<<<<<< Updated upstream
use App\Repository\OffreRepository;
use Doctrine\ORM\Mapping as ORM;
=======
>>>>>>> Stashed changes

 use App\Entity\Offre;
 use Symfony\Component\HttpFoundation\JsonResponse;
 use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
 use Symfony\Component\Serializer\Serializer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Validator\Constraints\Json;
use Doctrine\ORM\EntityManagerInterface as ORMEntityManagerInterface;
<<<<<<< Updated upstream
=======


>>>>>>> Stashed changes

class OffreController extends AbstractController
{
    #[Route('/offre', name: 'app_offre')]
    public function index(): Response
    {
        
        return $this->render('offre/index.html.twig', [
            'controller_name' => 'OffreController',
        ]);
    }


<<<<<<< Updated upstream
    
    




=======
>>>>>>> Stashed changes
    /******************Ajouter Offre*****************************************/
     /**
      * @Route("/addOffre", name="add_reclamation")
      * @Method("POST")
      */

<<<<<<< Updated upstream
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

=======
     public function ajouterOffre(Request $request)
     {
         $offre= new Offre();
         $objet = $request->query->get("objet");
         $em = $this->getDoctrine()->getManager();

         $offre->setObjet($objet);
         $offre->setDate($date);

         $em->persist($offre);
         $em->flush();
         $serializer = new Serializer([new ObjectNormalizer()]);
         $formatted = $serializer->normalize($offre);
         return new JsonResponse($formatted);

     }

     /******************Supprimer Reclamation*****************************************/

     /**
      * @Route("/deleteOffre", name="delete_reclamation")
      * @Method("DELETE")
      */

     public function deleteOffre(Request $request,ManagerRegistry $doctrine) {
>>>>>>> Stashed changes
        $page = $request->query->getInt('page', 1); 
         $offre = $request->get("offre");

         $em = $doctrine->getManager();
<<<<<<< Updated upstream
         $offre = $em->getRepository(Offre::class)->find($id);
=======
         $offre = $em->getRepository(Offre::class)->find($offre);
>>>>>>> Stashed changes
         if($offre!=null ) {
             $em->remove($offre);
             $em->flush();

             $serialize = new Serializer([new ObjectNormalizer()]);
             $formatted = $serialize->normalize("Reclamation a ete supprimee avec success.");
             return new JsonResponse($formatted);

         }
<<<<<<< Updated upstream
         return new JsonResponse("offre");
=======
         return new JsonResponse("offre.");
>>>>>>> Stashed changes


     }

<<<<<<< Updated upstream
=======
    /******************Modifier Reclamation*****************************************/
    /**
     * @Route("/updateOffre", name="update_Offre")
     * @Method("PUT")
     */
    public function modifierOffre(Request $request,ORMEntityManagerInterface $em,ManagerRegistry $doctrine) {
        $em = $doctrine->getManager();
        $offre =  $em
                        ->getRepository(Offre::class)
                        ->find($request->get("id"));


        $em->persist($offre);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($offre);
        return new JsonResponse("Reclamation a ete modifiee avec success.");

    }

>>>>>>> Stashed changes


    /******************affichage Reclamation*****************************************/

     /**
      * @Route("/displayOffre", name="display_reclamation")
      */
<<<<<<< Updated upstream
      public function allRecAction(ORMEntityManagerInterface $em)
      {
 
          $offre = $em->getRepository(Offre::class)->findAll();
          $serializer = new Serializer([new ObjectNormalizer()]);
          $formatted = $serializer->normalize($offre);
 
          return new JsonResponse($formatted);
 
      }
=======
     public function allRecAction(ORMEntityManagerInterface $em)
     {

         $offre = $em->getRepository(Offre::class)->findAll();
         $serializer = new Serializer([new ObjectNormalizer()]);
         $formatted = $serializer->normalize($offre);

         return new JsonResponse($formatted);

     }


 }
>>>>>>> Stashed changes
  
