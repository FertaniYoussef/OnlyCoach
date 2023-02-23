<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Rating;
use App\Repository\RatingRepository;
use App\Repository\CoursRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;


class RatingController extends AbstractController
{
    #[Route('/rating', name: 'app_rating')]
    public function index(Request $req,ManagerRegistry $doctrine, RatingRepository $rep, CoursRepository $coursRep): Response
    {
        $rating = new Rating();
        // if the request is a POST request
        if ($req->getMethod() === 'POST') {
            // get the inputs from the request
            $inputs = $req->request->all();
            // set the rating value
            $rating->setNote($inputs['rating']);
            // set the rating user as the current user
            $rating->setUser($this->getUser());
            // set the rating course as the course with the id from the request
            $cours = $coursRep->find($inputs['course']);
            $rating->setCours($cours);
            // get the entity manager
            $em = $doctrine->getManager();
            // persist the rating
            $em->persist($rating);
            // flush the rating
            $em->flush();
        }

        // redirect to previous page
        return $this->redirect($_SERVER['HTTP_REFERER']);
    }
}
