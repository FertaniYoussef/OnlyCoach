<?php

namespace App\Controller;

use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use App\Repository\CoursRepository;
use App\Repository\AbonnementRepository;
use App\Repository\AdherentsRepository;
use Doctrine\Persistence\ManagerRegistry;

class CoursesController extends AbstractController
{
    #[Route('/courses', name: 'app_courses')]
    public function index(): Response
    {
        return $this->render('courses/index.html.twig',array('userinfo'=>$this->getUser()));
    }

    #[Route('/courses/popular', name: 'app_courses_popular')]
    public function indexPopular(CategorieRepository $categorieRepository,CoursRepository $repository): Response
    {
        return $this->render('courses/popularCourses.html.twig', array('popular'=>$repository,'categories' => $categorieRepository->findAll(),'userinfo'=>$this->getUser()) );
    }


    #[Route('/courses/{id}', name: 'app_course')]
    public function indexCourse(CoursRepository $repository, int $id, AbonnementRepository $abonRep, AdherentsRepository $adRep, ManagerRegistry $doctrine): Response
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
        return $this->render('courses/course.html.twig', array('course' => $course, 'abonnement' => $abonnement, 'adherent' => $adherent, 'user' => $user));
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
