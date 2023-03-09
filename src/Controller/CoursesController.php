<?php

namespace App\Controller;

use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Categorie;
use App\Entity\Commentaire;
use App\Entity\Cours;
use App\Form\CommentaireType;
use App\Repository\CategorieRepository;
use App\Repository\CoursRepository;
use App\Repository\AbonnementRepository;
use App\Repository\AdherentsRepository;
use App\Repository\OffreRepository;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\Repository\CommentaireRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Doctrine\Persistence\ManagerRegistry;

class CoursesController extends AbstractController
{
    #[Route('/courses', name: 'app_courses')]
    public function index(): Response
    {
        return $this->render('courses/index.html.twig',array('userinfo'=>$this->getUser()));
    }

    #[Route('/courses/popular', name: 'app_courses_popular')]
    public function indexPopular(): Response
    {
        return $this->render('courses/popularCourses.html.twig', array('popular' => [['id' => '1', 'title' => 'Get started with Stretching. - Learn the basics in less than 24 Hours!', 'coach' => 'Amrou Ghribi', 'background' => 'StretchingImage.jpg', 'rating' => 4.3, 'totalratings' => 1098],['id' => '2', 'title' => 'Get started with Yoga. - Learn the basics in less than 24 Hours!', 'coach' => 'Aziz Rezgui', 'background' => 'YogaImage.jpg', 'rating' => 3.7, 'totalratings' => 6782],['id' => '3', 'title' => 'Get started with Resistance. - Learn the basics in less than 24 Hours!', 'coach' => 'Fatma Masmoudi', 'background' => 'ResistanceImage.jpg', 'rating' => 3.2, 'totalratings' => 4]], 'categories' => ['Cardio','Resistance','Yoga','Whole Body','Circuit Training','HIIT','Stretching'],'userinfo'=>$this->getUser()) );
    }
    function removeBadWords($comment) {
        //hedha tableau taa lklem li thebou yestnahha
        $badWords = array("bad", "words");
        $words = explode(" ", $comment->getContenu());
        foreach ($words as &$word) {
            if (in_array(strtolower($word), $badWords)) {
                $word = str_repeat("*", strlen($word));
            }
        }
        $newComment = implode(" ", $words);
        echo $newComment;
        $comment->setContenu(  $newComment);
        return $comment;
    }

    #[Route('/courses/{id}', name: 'app_course')]
    public function indexCourse(CoursRepository $repository, int $id, AbonnementRepository $abonRep, AdherentsRepository $adRep, ManagerRegistry $doctrine, EntityManagerInterface $EM, HttpFoundationRequest $request): Response
    {

        $user = $this->getUser();
        $cours = $repository->find($id);
        $coach = $cours->getIdCoach();
        $coachId = $coach->getId();
        $abonnement = $abonRep->findAbonnementByAdherentAndCoach($user->getId(), $coachId);
        $adherent = $adRep->findAdherentByCourseId($user, $id);

        $cours->setNbvues($cours->getNbvues() + 1);
        $doctrine->getManager()->flush();


        $course = $repository->find($id);
        $Commentaires = $EM->getRepository(Commentaire::class)->findBy(['idCoures' => $id]);

//ajouter
        $Commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $Commentaire);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $Commentaire->setAuteur("imen");
            $Commentaire->setIdCoures($course);
            $Commentaire->setDate(new DateTime());
            $this->removeBadWords($Commentaire);

            $EM->persist($Commentaire);
            $EM->flush();
            $Commentaires = $EM->getRepository(Commentaire::class)->findBy(['idCoures' => $slug]);
            $this->removeBadWords($Commentaire);
        }
        return $this->render('courses/course.html.twig', array('f' => $form->createView(), 'Commentaires' => $Commentaires,'course' => $course, 'abonnement' => $abonnement, 'adherent' => $adherent, 'user' => $user));
    }

    #[Route('/courses/category/{slug}', name: 'app_courses_category')]
    public function indexslug($slug ,CategorieRepository $categorieRepository, CoursRepository $repository): Response
    {
        $user = $this->getUser();
        $categorieId = $categorieRepository->findOneBy(['Type' => $slug]);
        dump($categorieId);
        $cours = $repository->findCoursByCategory($categorieId);
        dump($cours);
        return $this->render('courses/filterCategoryCourses.html.twig',array('slug' => $slug,'courses' => $cours,'userinfo' => $user, 'categories' => $categorieRepository->findAll()));
    }
}
