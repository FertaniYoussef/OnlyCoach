<?php

namespace App\Controller;
use App\Entity\User;
use App\Entity\Cours;
use App\Entity\Reponse;
use App\Entity\Ressources;
use App\Entity\Sections;
use App\Entity\Coach;
use App\Entity\Offre;
use App\Entity\Feedback;
use App\Form\CoachType;
use App\Form\OfferType;
use App\Form\FeedbackType;
use App\Form\ReponseType;
use App\Repository\CoursRepository;
use App\Repository\FeedbackRepository;
use App\Repository\ReponseRepository;
use App\Repository\RessourcesRepository;
use App\Repository\SectionsRepository;
use App\Repository\CoachRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Adherents;
use App\Repository\AdherentsRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class DashboardController extends AbstractController
{
    #[Route('/coach/dashboard', name: 'app_dashboard')]
    public function index(Request $request): Response
    {

        return $this->render('dashboard/coach/index.html.twig', [
            'user' => $this->getUser(),
        ]);
    }

    // Debut partie coach Gestion cours

    // api that fetches a course by id
    #[Route('/coach/dashboard/api/course/{id}', name: 'app_dashboard_api_course')]
    public function apiCourse(Request $request, CoursRepository $repository, int $id): Response
    {
        $course = $repository->find($id);

        // loop through the course sections and extract the resources
        $sections = $course->getIdSections()->getValues();
        $resources = [];
        foreach ($sections as $section) {
            $resources[] = $section->getIdRessources()->getValues();
        }

        // loop through sections with a counter
        $sectionsArray = [];
        $counter = 0;
        foreach ($sections as $section) {
                $sectionsArray[$counter] = [
                    'id' => $section->getId(),
                    'title' => $section->getTitre(),
                ];
                $counter++;
        }
     //  print(json_encode($sectionsArray));

        // loop through resources with a counter
        $resourcesArray = [];
        $counter = 0;
        foreach ($resources as $resource) {
            foreach ($resource as $res) {
                $resourcesArray[$counter] = [
                    'id' => $res->getId(),
                    'description' => $res->getDescription(),
                    'link' => $res->getLien(),
                ];
                $counter++;
            }
        }

      //  print(json_encode($resourcesArray));





        $courseArray = [
            'id' => $course->getId(),
            'title' => $course->getTitre(),
            'description' => $course->getDescription(),
            'image' => $course->getCoursPhoto(),
            'date' => $course->getDateCreation(),
            'sections' => $sectionsArray,
            'resources' => $resourcesArray,
        ];
        return $this->json($courseArray);
    }

    // make an api that returns a json response
    #[Route('/coach/dashboard/api/courses', name: 'app_dashboard_api_courses')]
    public function apiCourses(Request $request, CoursRepository $repository): Response
    {
        $courses = $repository->findAll();
        // loop through the courses and each time append it to an array with json format
        $coursesArray = [];
        foreach ($courses as $course) {
            $coursesArray[] = [
                'id' => $course->getId(),
                'title' => $course->getTitre(),
                'description' => $course->getDescription(),
                'image' => $course->getCoursPhoto(),
                'date' => $course->getDateCreation(),
            ];
        }
        return $this->json($coursesArray);
    }

    #[Route('/coach/dashboard/courses', name: 'app_dashboard_listCourses')]
    public function listCourses(Request $request, CoursRepository $repository): Response
    {
        $user = $this->getUser();
        // get coach from user by id
        $coach = $repository->find($user->getId());
        $courses = $repository->findBy(array('IdCoach' => $coach));
        $courses = $repository->findAll();
        return $this->render('dashboard/coach/courses.html.twig', ['courses' => $courses,'user' => $this->getUser(),]);
    }

    #[Route('/coach/dashboard/courses/{id}', name: 'app_dashboard_course')]
    public function Course(Request $request, CoursRepository $repository, SectionsRepository $sectionRepository,RessourcesRepository $resourceRepository, int $id,AdherentsRepository $adhrepo,UserRepository $userrepo,ManagerRegistry $doctrine): Response
    {
        $course = $repository->find($id);
        $adherents=$adhrepo->findByCourse($course);


        $users=[];
        $entityManager = $doctrine->getManager();
        foreach($adherents as $adherent) {
            $userProxy= $adherent->getUser();

            $entityManager->initializeObject($userProxy);

            $users[]=$userProxy;
        }
        $sections = $course->getIdSections()->getValues();
        $resources = $resourceRepository->findBy(array('sections' => $sections));
        dump($sections);
        dump($resources);
        return $this->render('dashboard/coach/course.html.twig', ['users'=>$users,'course' => $course, 'sections' => $sections, 'resources' => $resources,'user' => $this->getUser(),]);
    }

    #[Route('/coach/dashboard/deleteCourse/{id}', name: 'app_dashboard_deleteCourse')]
    public function DeleteCourse(ManagerRegistry $doctrine, CoursRepository $repository, int $id) {
        $cours= $repository->find($id);
        $em = $doctrine->getManager();
        $em->remove($cours);
        $em->flush();
        return  $this->redirectToRoute("app_dashboard_listCourses");
    }

    #[Route('/coach/dashboard/deleteCourse/{idc}/{ids}', name: 'app_dashboard_deleteSection')]
    public function deleteSection(ManagerRegistry $doctrine, SectionsRepository $repository, int $idc, int $ids) {
        $section= $repository->find($ids);
        $em = $doctrine->getManager();
        $em->remove($section);
        $em->flush();
        return  $this->redirectToRoute("app_dashboard_modifycourse", ['id' => $idc]);
    }


    #[Route('/coach/dashboard/modifySection/{idc}/{ids}', name: 'app_dashboard_modifySection')]
    public function modifySection(ManagerRegistry $doctrine,CoursRepository $coursRepository, SectionsRepository $repository,RessourcesRepository $resourceRepository, int $idc, int $ids, Request $request) {
        $cours = $coursRepository->find($idc);
        $section = $repository->find($ids);
        dump($section);
        $resource = $resourceRepository->findBy(array('sections' => $section));
        dump($resource);
        if ($request->getMethod() === "POST") {
            $inputs = $request->request->all();
            $section->setTitre($inputs['section-title']);
            $resource[0]->setLien($inputs['section-link']);
            $resource[0]->setDescription($inputs['section-description']);
            $em = $doctrine->getManager();
            $em->flush();
            return  $this->redirectToRoute("app_dashboard_modifycourse", ['id' => $idc]);
        }
        return  $this->render('dashboard/coach/modifysection.html.twig', ['course' => $cours, 'section' => $section, 'resource' => $resource,'user' => $this->getUser(),]);
    }

    #[Route('/coach/dashboard/modifycourse/{id}', name: 'app_dashboard_modifycourse')]
    public function modifyCourse(Request $request,ManagerRegistry $doctrine, CoursRepository $repository, SectionsRepository $sectionRepository,RessourcesRepository $resourceRepository, int $id) {
        $course = $repository->find($id);
        $sections = $course->getIdSections()->getValues();
        $resources = $resourceRepository->findBy(array('sections' => $sections));
        dump($course);
        dump($sections);
        dump($resources);

        // WIP

        if ($request->getMethod() === 'POST') {
            dump($request->request->all());
            $inputs = $request->request->all();
            $course->setTitre($inputs['course-name']);
            $course->setDescription($inputs['course-description']);

            /* Uploading image */
            dump($_FILES);
            $target_dir = "./images/"; // update if needed with coach/user name
            $target_file = $target_dir . basename($_FILES["course-background"]["name"]);
            dump($_FILES["course-background"]["name"]);
            move_uploaded_file($_FILES["course-background"]["tmp_name"], $target_file);
            /* */
        if ($_FILES["course-background"]["name"])
            $course->setCoursPhoto($_FILES["course-background"]["name"]);
        else
            $course->setCoursPhoto($course->getCoursPhoto());

            $course->setDateCreation($course->getDateCreation());
            $course->setNbVues($course->getNbVues());


            $em = $doctrine->getManager();

            $em->flush();
            $em->clear();


            return $this->redirectToRoute('app_dashboard');
        }

        // WIP

        return $this->render('dashboard/coach/modify.html.twig', ['course' => $course, 'sections' => $sections, 'resources' => $resources,'user' => $this->getUser(),]);
    }

    #[Route('/coach/dashboard/addCourse', name: 'app_dashboard_addcourse')]
    public function AddCourse(Request $request, ManagerRegistry $doctrine, ValidatorInterface $validator, CoachRepository $repository)
    {
        if ($request->getMethod() === 'POST') {

            dump($request->request->all());
            $inputs = $request->request->all();
            $cours = new Cours();
            $cours->setTitre($inputs['course-name']);
            $cours->setDescription($inputs['course-description']);

            // get current user from token
            $user = $this->getUser();
            dump($user);

            // get coach from user by id
            $coach = $repository->findBy(array('id_user' => $user));
            dump($coach);
            $coach = $coach[0];
            $cours->setIdCoach($coach);
            $coach->addCour($cours);


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

            dump($cours);

                        // validate $cours
                $errors = $validator->validate($cours);

                try {
                $errorString = (string) $errors[0];
                    }
                catch (\Exception $e) {
                    $errorString = "";
                }
                // replace "Object(App\Entity\Cours)." with ""
                $errorString = str_replace('Object(App\Entity\Cours).', '', $errorString);

                if (count($errors) > 0) {
                        // check if errorString contains "cours_photo" if so, replace it with "background"
                        if (strpos($errorString, 'cours_photo') !== false) {
                                $errorString = str_replace('cours_photo', 'background', $errorString);
                                $errorString = str_replace('This value should not be blank.', 'This value should not be blank. Please upload a background image.', $errorString);
                        }
                    return $this->redirectToRoute('app_dashboard', ['errors' => $errorString]);
                }

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


            return $this->redirectToRoute('app_dashboard', ['success' => "Course added successfully!"]);
        }
        return $this->redirectToRoute('app_login');
    }

    // Fin partie coach



    // Partie admin

    #[Route('/admin/dashboard', name: 'app_dashboard_adminIndex')]
    public function adminIndex(Request $request): Response
    {
        return $this->render('dashboard/admin/index.html.twig',array('userinfo'=>$this->getUser()));
    }

    // Partie users
    #[Route('/admin/dashboard/users', name: 'app_dashboard_adminUsers')]
    public function users(Request $request,UserRepository $repository): Response
    {
        $users = $repository->findAll();
        return $this->render('dashboard/admin/users/users.html.twig',[
            'userstab' => $users,'userinfo'=>$this->getUser()
        ]);
    }
    
    #[Route('/admin/dashboard/users', name: 'app_dashboard_adminUsers')]
    public function userss(Request $request,UserRepository $repository,SerializerInterface $serializer): Response
    {
        $users = $repository->findAll();
        $jsonContent = $serializer->serialize($users, 'json');
        dd($jsonContent);
    }

    #[Route('/admin/dashboard/users/remove/{id}', name: 'app_dashboard_adminUsersremove')]
    public function usersremove(ManagerRegistry $doctrine,$id,UserRepository $repository)
    {
        $users= $repository->find($id);
        $em = $doctrine->getManager();
        $em->remove($users);
        $em->flush();
        return  $this->redirectToRoute('app_dashboard_adminUsers');
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
    public function feedbacks(Request $request,FeedbackRepository $repository): Response
    {
        $Feedback=$repository->findAll();
        return $this->render('dashboard/admin/feedback/feedbacks.html.twig',[
            'Feedback' => $Feedback,

        ]);
    }

   #[Route('/admin/dashboard/feedback/consulter/{id}', name: 'app_dashboard_adminConsulterFeedback')]
    public function consulterFeedback(Request $request,$id,Feedback $feedback,EntityManagerInterface $EM,ManagerRegistry $doctrine,ReponseRepository $repository): Response
    {
        $reponse = new Reponse();
        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // ajouter reponse
            $EM->persist($reponse);
            $EM->flush();
             $reponse->getIdFeedback();
             $feedback->setStatus(1);
            return $this->redirectToRoute('app_dashboard_adminFeedbacks');
        }

        if ($form->getClickedButton() && 'delete' === $form->getClickedButton()->getName()) {
            // supprimer reponse
            $reponse= $repository->find($id);
            $em = $doctrine->getManager();
            $em->remove($reponse);
            $em->flush();
            return $this->redirectToRoute('app_dashboard_adminFeedbacks');
        }
        return $this->render('dashboard/admin/feedback/consulterFeedback.html.twig', [
            'form' => $form->createView(),
        ]);

    }
   /* #[Route("/admin/dashboard/feedbacks/consulter_stat/{id}",name:"consulter_stat")]
    public function consulter(Feedback $feedback, ManagerRegistry $doctrine)
    {
        $feedback->setStatus(1);
        $em = $doctrine->getManager();
        $em->flush();
        return  $this->redirectToRoute("app_dashboard_adminFeedbacks");
    }*/
    #[Route('/admin/dashboard/feedbacks/remove/{id}', name: 'app_dashboard_admin_removeFeedbacks')]
    public function removeFeedback(ManagerRegistry $doctrine,$id,FeedbackRepository $repository)
    {
        $Feedback= $repository->find($id);
        $em = $doctrine->getManager();
        $em->remove($Feedback);
        $em->flush();
        $this->addFlash(
            'info',
            ' le Reclamation a été supprimer',
        );
        return  $this->redirectToRoute("app_dashboard_adminFeedbacks");
    }

    /**
     * @Route("/admin/dashboard/feedbacks/search", name="feedback_search", methods={"GET"})
     */
    public function search(Request $request)
    {
        $query = $request->query->get('q');

        $repository = $this->entityManager->getRepository(Feedback::class);

        if (is_numeric($query)) {
            $results = $repository->findBy(['id' => $query]);
        } else {
            $results = $repository->findBy(['Sujet' => $query]);
        }

        return $this->render('dashboard/admin/feedback/feedbacks.html.twig', [
            'Feedback' => $results,
        ]);
    }


    /**
     * @Route("/feedbacks/{sort}", name="feedbacks_list", defaults={"sort"="date_desc"})
     */
    public function listFeedbacks(EntityManagerInterface $entityManager, string $sort = 'date_desc'): Response
    {
        $feedbacksRepository = $entityManager->getRepository(Feedback::class);

        $queryBuilder = $feedbacksRepository->createQueryBuilder('f')
            ->orderBy('f.date_feedback', ($sort === 'date_asc') ? 'ASC' : 'DESC');

        $feedbacks = $queryBuilder->getQuery()->getResult();

        return $this->render('dashboard/admin/feedback/feedbacks.html.twig', [
            'Feedback' => $feedbacks,
            'sort' => $sort
        ]);
    }

    // fin partie admin


}
