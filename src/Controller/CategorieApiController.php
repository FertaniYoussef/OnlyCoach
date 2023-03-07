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
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Annotation\Groups;



class CategorieApiController extends AbstractController
{
    #[Route('/categorie/api', name: 'app_categorie_api')]
    public function index(CategorieRepository $repository,SerializerInterface $serializerInterface): Response
    {
        
            $categories = $repository->findAll();
            $json = $serializerInterface->serialize($categories,'json',[
                'groups' => ['main'],
                'root' => true,
            ]);
        
            return new Response($json);
    }
        #[Route('/categorie/get/{id}',name:'app_categorie_get_json')]
        public function recupererJson($id,NormalizerInterface $normalizerInterface,CategorieRepository $repo)
        {
            $categorie = $repo->find($id);
            $categorienormalizer = $normalizerInterface->normalize($categorie,'json');
            return new Response(json_encode($categorienormalizer));
        }
        #[Route('/categorie/add',name:'app_categorie_add_json')]
        public function ajouterJson(Request $request,NormalizerInterface $normalizerInterface,ManagerRegistry $doctrine)
        {
            $em = $doctrine->getManager();
            $type = $request->get('type');
        
            if (!$type) {
                return new Response('Le champ "type" est requis', Response::HTTP_BAD_REQUEST);
            }
        
            $categorie = new Categorie();
            $categorie->setType($type);
        
            $em->persist($categorie);
            $em->flush();
        
            $jsonContent = $normalizerInterface->normalize($categorie, 'json');
            return new Response(json_encode($jsonContent));
        }
    
        
        #[Route('/categorie/update/{id}',name:'app_categorie_update_json')]
        public function modifierJson(Request $request,$id,NormalizerInterface $normalizerInterface,ManagerRegistry $doctrine)
        {
            $em = $doctrine->getManager();
            $categorie = $em->getRepository(Categorie::class)->find($id);
            $categorie->setType($request->get('type'));
            
            $em->flush();
            $jsonContent = $normalizerInterface->normalize($categorie);
            return new Response(" Categorie modifiée avec succès".json_encode($jsonContent));
        }
        #[Route('/categorie/delete/{id}',name:'app_categorie_delete_json')]
        public function supprimerJson(Request $request,$id,NormalizerInterface $normalizerInterface,ManagerRegistry $doctrine)
        {
            $em = $doctrine->getManager();
            $categorie = $em->getRepository(Categorie::class)->find($id);
            $em->remove($categorie);
            $em->flush();
            $jsonContent = $normalizerInterface->normalize($categorie,['root' => true,]);
            return new Response("Catégorie supprimée avec succès".json_encode($jsonContent));
        }
    }

