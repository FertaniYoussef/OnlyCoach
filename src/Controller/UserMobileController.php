<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Form\UserType;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserMobileController extends AbstractController
{
    #[Route('/mobile/login', name: 'api_login')]
    public function Login(Request $request, UserRepository $repository,SerializerInterface $serializer){
        $email = $request->query->get("email");
        $password = $request->query->get("password");

        $user = $repository->findOneBy(['email'=>$email]);
        if($user){
            if(password_verify($password,$user->getPassword())){
                $serializer = new Serializer([new ObjectNormalizer()]);
                $formatted = $serializer->normalize($user);
                return new JsonResponse($formatted);
            }
            else{
                return new Response("password not found");
            }
        }
        else{
            return new Response("user not found");
        }
    }
    
    #[Route('/mobile/inscription', name: 'User_mobile_inscription')]
    public function Register(UserRepository $repository ,ManagerRegistry $doctrine, Request $request,UserPasswordHasherInterface $passwordHasher,SerializerInterface $serializer)
    {   
        $email = $request->query->get("email");
        $password = $request->query->get("password");
        $nom = $request->query->get("nom");
        $prenom = $request->query->get("prenom");
        $user = new User();
        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $password
        );
        
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            return new Response("email invalid.");
        }


        
        $user->setRoles(['ROLE_USER']);
        $user->setEmail($email);
        //$user->setNom("aziz");
        //$user->setPrenom("aziz");
        $user->setNom($nom);
        $user->setPrenom($prenom);
        $user->setPassword($hashedPassword);
        try{
            
            $repository->add($user, true);

            return new JsonResponse("account is created", 200);
        }catch(\Exception $ex){
            return new Response("execption".$ex->getMessage());
        }
    }
    #[Route('/mobile/logout', name: 'app_logout', methods: ['GET'])]
    public function logout()
    {
        // controller can be blank: it will never be called!
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }


    #[Route('/api/getPasswordByEmail', name: 'api_password')]
    public function getPasswordByEmail(Request $request,UserRepository $repository){

        $email = $request->get('email');
        $user = $repository->findOneBy(['email'=>$email]);
        if($user){
            $password = $user->getPassword();
            $serializer = new Serializer([new ObjectNormalizer()]);
            $formatted = $serializer->normalize($password);
            return new JsonResponse($formatted);
        }
        return new Response("user not found");
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
