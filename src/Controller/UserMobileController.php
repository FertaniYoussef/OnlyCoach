<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Form\UserType;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\Persistence\ManagerRegistry;

class UserMobileController extends AbstractController
{
    #[Route('/mobile/login', name: 'app_mobile_login')]
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
    
    #[Route('/mobile/inscription', name: 'User_mobile_inscription')]
    public function AddUser(UserRepository $repository, ManagerRegistry $doctrine, Request $request,UserPasswordHasherInterface $passwordHasher,ValidatorInterface $validator,SerializerInterface $serializer)
    {
            $jsonRecu = $request->getContent();
            $inputs = $serializer->deserialize($jsonRecu, User::class, 'json');
            $user = new User();
            $plaintextPassword = $inputs->getPassword();
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $plaintextPassword
            );
            $role = $user->getRoles();
            $user->setRoles($role);
            $user->setPassword($hashedPassword);
            $user->setPicture('images\defaultuser.png');
            $user->setEmail($inputs->getEmail());
            $user->setNom($inputs->getPrenom());
            $user->setPrenom($inputs->getNom());
            $repository->add($user, true);
            return  $this->json('Creation avec succes', 201, []);
    }
    #[Route('/mobile/logout', name: 'app_logout', methods: ['GET'])]
    public function logout()
    {
        // controller can be blank: it will never be called!
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }
    #[Route('/mobile/user/settings/modify', name: 'app_settings_mobile_modify')]
    public function indexsettingsmodify(Request $request,UserRepository $repository, ManagerRegistry $doctrine,UserPasswordHasherInterface $passwordHasher,ValidatorInterface $validator): Response
    {
        $user = $this->getUser();
        if ($request->getMethod() === 'POST'){
            $request->request->all();
            $inputs = $request->request->all();
            $target_dir = "./images/"; // update if needed with coach/user name
            $target_file = $target_dir . basename($_FILES["user-photo"]["name"]);
            move_uploaded_file($_FILES["user-photo"]["tmp_name"], $target_file);
        if ($_FILES["user-photo"]["name"])
            $user->setPicture($target_file);
        else
            $user->setPicture($user->getPicture());

        if ($inputs["first-name"])
            $user->setPrenom($inputs["first-name"]);
        else
            $user->setPrenom($user->getPrenom());

        if ($inputs["last-name"])
            $user->setNom($inputs["last-name"]);
        else
            $user->setNom($user->getNom()); 

        if ($inputs["phone"])
            $user->setPhone($inputs["phone"]);
        else
            $user->setPhone($user->getPhone()); 
             
        if ($inputs["about"])
            $user->setdescription($inputs["about"]);
        else
            $user->setdescription($user->getdescription());
        if ($inputs["oldPassword"]){
            $match = $passwordHasher->isPasswordValid($user, $inputs["oldPassword"]);
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $inputs["newPassword"]
            );
            $user->setPassword($hashedPassword);
        }
        else
        $user->setPassword($user->getPassword());  
        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            return $this->render('user/settings.html.twig',array('userinfo'=>$this->getUser(),'errors' => $errors));
        }
        $em = $doctrine->getManager();
        $em->flush();
        $em->clear();


            return $this->redirectToRoute('app_settings',array('userinfo'=>$this->getUser()));
        }
    }
}
