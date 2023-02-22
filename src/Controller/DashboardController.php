<?php

namespace App\Controller;
use App\Entity\User;
use App\Entity\Cours;
use App\Entity\Ressources;
use App\Entity\Sections;
use App\Repository\CoursRepository;
use App\Repository\RessourcesRepository;
use App\Repository\SectionsRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

class DashboardController extends AbstractController
{
    #[Route('/coach/dashboard', name: 'app_dashboard')]
    public function index(Request $request): Response
    {
        return $this->render('dashboard/coach/index.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }

    #[Route('/coach/dashboard/courses', name: 'app_dashboard_listCourses')]
    public function listCourses(Request $request, CoursRepository $repository): Response
    {
        $courses = $repository->findAll();
        return $this->render('dashboard/coach/courses.html.twig', ['courses' => $courses,]);
    }

    #[Route('/coach/dashboard/courses/{id}', name: 'app_dashboard_course')]
    public function Course(Request $request, CoursRepository $repository, SectionsRepository $sectionRepository,RessourcesRepository $resourceRepository, int $id): Response
    {
        $course = $repository->find($id);
        $sections = $course->getIdSections()->getValues();
        $resources = $resourceRepository->findBy(array('sections' => $sections));
        dump($sections);
        dump($resources);
        return $this->render('dashboard/coach/course.html.twig', ['course' => $course, 'sections' => $sections, 'resources' => $resources]);
    }

    #[Route('/coach/dashboard/deleteCourse/{id}', name: 'app_dashboard_deleteCourse')]
    public function DeleteCourse(ManagerRegistry $doctrine, CoursRepository $repository, int $id) {
        $cours= $repository->find($id);
        $em = $doctrine->getManager();
        $em->remove($cours);
        $em->flush();
        return  $this->redirectToRoute("app_dashboard_listCourses");
    }

    #[Route('/coach/dashboard/modifycourse/{id}', name: 'app_dashboard_modifyCourse')]
    public function modifyCourse(ManagerRegistry $doctrine, CoursRepository $repository, SectionsRepository $sectionRepository,RessourcesRepository $resourceRepository, int $id) {
        $course = $repository->find($id);
        $sections = $course->getIdSections()->getValues();
        $resources = $resourceRepository->findBy(array('sections' => $sections));
        dump($sections);
        dump($resources);
        return $this->render('dashboard/coach/modify.html.twig', ['course' => $course, 'sections' => $sections, 'resources' => $resources]);
    }

    #[Route('/coach/dashboard/addCourse', name: 'app_dashboard_addcourse')]
    public function AddCourse(Request $request, ManagerRegistry $doctrine)
    {
        if ($request->getMethod() === 'POST') {

            dump($request->request->all());
            $inputs = $request->request->all();
            $cours = new Cours();
            $cours->setTitre($inputs['course-name']);
            $cours->setDescription($inputs['course-description']);

            /* Uploading image */
            dump($_FILES);
            $target_dir = "./images/"; // update if needed with coach/user name
            $target_file = $target_dir . basename($_FILES["course-background"]["name"]);
            dump($_FILES["course-background"]["name"]);
            move_uploaded_file($_FILES["course-background"]["tmp_name"], $target_file);
            /* */

            $cours->setCoursPhoto($_FILES["course-background"]["name"]);
            $cours->setDateCreation(new \DateTime());
            $cours->setNbVues(1);

            array_shift($inputs);
            array_shift($inputs);

            dump($inputs);
            $em = $doctrine->getManager();
            $em->persist($cours);
            // section & resource management
            dump('id du cours ajouté est : '.$cours->getId());

            for ($i = 1; $i <= count($inputs)/3; $i += 1) {
                // section
                $section = new Sections();

                $section->setTitre($inputs['section'.$i.'-title']);
                $section->setCours($cours);
                $section->setIndexSection($i);
                $section->setNbresources(1);
                $cours->addIdSection($section);
                $em->persist($section);

                // resource
                $resource = new Ressources();
                $resource->setLien($inputs['section'.$i.'-link']);
                $resource->setDescription($inputs['section'.$i.'-description']);
                $resource->setSections($section);
                $resource->setIndexRessources(1);
                $section->addIdRessource($resource);
                $em->persist($resource);
            }

            $em->flush();
            $em->clear();


            return $this->redirectToRoute('app_dashboard');
        }
        return $this->redirectToRoute('app_login');
    }
<<<<<<< Updated upstream
=======

    // Fin partie coach



    // Partie admin

    #[Route('/admin/dashboard', name: 'app_dashboard_adminIndex')]
    public function adminIndex(Request $request): Response
    {
        return $this->render('dashboard/admin/index.html.twig');
    }

    // Partie users
    #[Route('/admin/dashboard/users', name: 'app_dashboard_adminUsers')]
    public function users(Request $request): Response
    {
        return $this->render('dashboard/admin/users/users.html.twig');
    }



    // Partie coachs

    #[Route('/admin/dashboard/coachs', name: 'app_dashboard_adminCoachs')]
    public function coachs(Request $request,ManagerRegistry $doctrine, CoachRepository $repository, UserRepository $userRepo): Response
    {
        $coachs = $repository->findAll();
        $coach = new Coach();

        $form = $this->createForm(CoachType::class, $coach);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($form->getErrors(true) as $error) {
                $this->addFlash('error', $error->getMessage());
            }
            $coach = $form->getData();
            $user = $userRepo->find($coach->getIdUser());
            $user->setRoles(['ROLE_COACH']);
            // get user data and put them in coach (only in coach table)
            $coach->setNom($user->getNom());
            $coach->setPrenom($user->getPrenom());
            // store uploaded picture in public/images

            $file = $form->get('picture')->getData();
            dump($file);
            $coach->setPicture($file->getClientOriginalName());
            $file->move('images', $file->getClientOriginalName());



            $em = $doctrine->getManager();
            $em->persist($coach);
            $em->flush();

            return $this->redirectToRoute('app_dashboard_adminCoachs');
            
        }


        return $this->render('dashboard/admin/coachs/coachs.html.twig', [
            'form' => $form->createView(),
            'coachs' => $coachs
        ]);
    }

    #[Route('/admin/dashboard/coachs/delete/{id}', name: 'app_dashboard_adminCoachsDelete')]
    public function deleteCoach(Request $request,ManagerRegistry $doctrine, CoachRepository $repository, UserRepository $userRepo, int $id): Response
    {
        $coach = $repository->find($id);
        $user = $userRepo->find($coach->getIdUser());
        $user->setRoles(['ROLE_USER']);
        $em = $doctrine->getManager();
        $em->remove($coach);
        $em->flush();
        return $this->redirectToRoute('app_dashboard_adminCoachs');

    }


    // Partie Offers

    #[Route('/admin/dashboard/offers', name: 'app_dashboard_adminOffers')]
    public function offers(Request $request): Response
    {
        $offre = new Offre();
        $form = $this->createForm(OfferType::class, $offre);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            return $this->redirectToRoute('app_dashboard_adminOffers');
        }
        return $this->render('dashboard/admin/offers/offers.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/dashboard/offers/modify/{id}', name: 'app_dashboard_adminModifierOffer')]
    public function offersModify(Request $request,int $id): Response
    {
        $offre = new Offre();
        $form = $this->createForm(OfferType::class, $offre);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            return $this->redirectToRoute('app_dashboard_adminOffers');
        }
        return $this->render('dashboard/admin/offers/modifyoffer.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Partie feedbacks

    #[Route('/admin/dashboard/feedbacks', name: 'app_dashboard_adminFeedbacks')]
    public function feedbacks(Request $request): Response
    {
        return $this->render('dashboard/admin/feedback/feedbacks.html.twig');
    }

    #[Route('/admin/dashboard/feedback/consulter/{id}', name: 'app_dashboard_adminConsulterFeedback')]
    public function consulterFeedback(Request $request,int $id): Response
    {
        $feedback = new Feedback();
        $form = $this->createForm(FeedbackType::class, $feedback);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            return $this->redirectToRoute('app_dashboard_adminFeedbacks');
        }
        return $this->render('dashboard/admin/feedback/consulterFeedback.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    // fin partie admin


>>>>>>> Stashed changes
}
