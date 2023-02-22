<?php

namespace App\Controller;

use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Categorie;
use App\Repository\CategorieRepository;

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


    #[Route('/courses/{slug}', name: 'app_course')]
    public function indexCourse($slug): Response
    {
        return $this->render('courses/course.html.twig', array('course' => ['id' => '1', 'title' => 'Get started with Resistance. - Learn the basics in less than 24 Hours!', 'coach' => 'Amrou Ghribi','coachcategory' => 'Resistance', 'background' => 'ResistanceImage.jpg', 'rating' => 4.3, 'totalratings' => 1098, 'members' => 2490, 'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer ipsum diam, ultrices sed eleifend quis, placerat sit amet est. Nam mi mi, lobortis in mi a, condimentum commodo ex. In hac habitasse platea dictumst. Nam lobortis tincidunt auctor nunc.'],'userinfo'=>$this->getUser()));
    }

    #[Route('/courses/category/{slug}', name: 'app_courses_category')]
    public function indexslug($slug ,CategorieRepository $categorieRepository): Response
    {
        return $this->render('courses/filterCategoryCourses.html.twig',array('slug' => $slug,'popular' => [['id' => '1', 'title' => 'Get started with Stretching. - Learn the basics in less than 24 Hours!', 'coach' => 'Amrou Ghribi', 'background' => 'StretchingImage.jpg', 'rating' => 4.3, 'totalratings' => 1098],['id' => '2', 'title' => 'Get started with Yoga. - Learn the basics in less than 24 Hours!', 'coach' => 'Aziz Rezgui', 'background' => 'YogaImage.jpg', 'rating' => 3.7, 'totalratings' => 6782],['id' => '3', 'title' => 'Get started with Resistance. - Learn the basics in less than 24 Hours!', 'coach' => 'Fatma Masmoudi', 'background' => 'ResistanceImage.jpg', 'rating' => 3.2, 'totalratings' => 4]], 'categories' => $categorieRepository->findAll(),'userinfo'=>$this->getUser()));
    }
}
