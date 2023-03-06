<?php

namespace App\Controller;
use App\Entity\Feedback;
use App\Entity\User;
use App\Form\ReponseType;
use App\Repository\FeedbackRepository;
use App\Repository\ReponseRepository;
//use ContainerMOhcjFC\getReponseRepositoryService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;



class ContactController extends AbstractController
{

    function removeBadWords($comment) {
        //hedha tableau taa lklem li thebou yestnahha
        $badWords = array("bad", "words");
        $words = explode(" ", $comment->getDescription());
        foreach ($words as &$word) {
            if (in_array(strtolower($word), $badWords)) {
                $word = str_repeat("*", strlen($word));
            }
        }
        $newComment = implode(" ", $words);
        echo $newComment;
        $comment->setDescription(  $newComment);
        return $comment;
    }


    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, FeedbackRepository $rep, ManagerRegistry $doctrine, ValidatorInterface $validator): Response
    {
        $feedback = new Feedback();
        if ($request->getMethod() === 'POST') {
            $inputs = $request->request->all();
            dump($inputs);
            $feedback->setSujet($inputs['sujet']);
            $feedback->setDescription($inputs['message']);
            // set feedback email as the current user email
            $feedback->setEmail($this->getUser()->getemail());
            $feedback->setUser($this->getUser());
            $feedback->setStatus(0);
            $feedback->setDateFeedback(new \DateTime());
            $this->removeBadWords($feedback);
            $em = $doctrine->getManager();

            //SEND MAIL:
            $em->persist($feedback);
            $em->flush();
            $this->addFlash('success','Merci pour votre Feedback ! !');

            $errors = $validator->validate($feedback);

            if (count($errors) > 0) {
                return $this->render('contact/index.html.twig', [
                    'errors' => $errors,
                ]);
            }return $this->redirectToRoute('app_contact', [], Response::HTTP_SEE_OTHER);

        }return $this->render('contact/index.html.twig',
            ['controller_name' => 'ContactController',
                'errors'=>null,
            ]);
    }





}
