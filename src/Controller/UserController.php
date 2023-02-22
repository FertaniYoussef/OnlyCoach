<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Form\UserType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('login/index.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
            ]);
    }
    
    #[Route('/inscription', name: 'User_inscription')]
    public function AddUser(UserRepository $repository, ManagerRegistry $doctrine, Request $request,UserPasswordHasherInterface $passwordHasher)
    {
        if ($request->getMethod() === 'POST'){
            $request->request->all();
            $inputs = $request->request->all();   
            $user = new User();
            $plaintextPassword = $inputs["password"];
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $plaintextPassword
            );
            $role = $user->getRoles();
            $user->setRoles($role);
            $user->setPassword($hashedPassword);
            $user->setPicture('images\defaultuser.png');
            $user->setEmail($inputs["email"]);
            $user->setNom($inputs["lastname"]);
            $user->setPrenom($inputs["firstname"]);
            $repository->add($user, true);
            return  $this->redirectToRoute("app_login");
        }
    }
    #[Route('/logout', name: 'app_logout', methods: ['GET'])]
    public function logout()
    {
        // controller can be blank: it will never be called!
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }
    #[Route('/user/settings', name: 'app_settings')]
    public function indexsettings(): Response
    {
        return $this->render('user/settings.html.twig');
    }

    #[Route('/user/{id}', name: 'app_user')]
    public function index($id): Response
    {
        return $this->render('user/index.html.twig', array('popular' => [['id' => '1', 'title' => 'Get started with Stretching. - Learn the basics in less than 24 Hours!', 'creator' => 'Amrou Ghribi', 'background' => 'StretchingImage.jpg', 'rating' => 4.3, 'totalratings' => 1098],['id' => '2', 'title' => 'Get started with Yoga. - Learn the basics in less than 24 Hours!', 'creator' => 'Aziz Rezgui', 'background' => 'YogaImage.jpg', 'rating' => 3.7, 'totalratings' => 6782],['id' => '3', 'title' => 'Get started with Resistance. - Learn the basics in less than 24 Hours!', 'creator' => 'Fatma Masmoudi', 'background' => 'ResistanceImage.jpg', 'rating' => 3.2, 'totalratings' => 4]], 'categories' => ['Cardio','Resistance','Yoga','Whole Body','Circuit Training','HIIT','Stretching']) );
    }
}
