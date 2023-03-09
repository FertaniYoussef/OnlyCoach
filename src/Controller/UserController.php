<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\CoachRepository;
use App\Form\UserType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

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
    public function AddUser(UserRepository $repository, ManagerRegistry $doctrine, Request $request,UserPasswordHasherInterface $passwordHasher,ValidatorInterface $validator)
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
            $errors = $validator->validate($user);
            if (count($errors) > 0) {
                return $this->render('register/index.html.twig', [
                    'errors' => $errors,
                ]);
            }
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
    #[Route('/user/settings/modify', name: 'app_settings_modify')]
    public function indexsettingsmodify(Request $request,UserRepository $repository, ManagerRegistry $doctrine,UserPasswordHasherInterface $passwordHasher,ValidatorInterface $validator): Response
    {
        $match=null;
        $user = $this->getUser();
        if ($request->getMethod() === 'POST') {
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
            if ($inputs["oldPassword"]) {
                $match = $passwordHasher->isPasswordValid($user, $inputs["oldPassword"]);
                if($match==true){
                    $hashedPassword = $passwordHasher->hashPassword(
                        $user,
                        $inputs["newPassword"]
                    );
                    $user->setPassword($hashedPassword);
                }
            } else
                $user->setPassword($user->getPassword());
            $errors = $validator->validate($user);
            if (count($errors) > 0) {
                return $this->render('user/settings.html.twig', array('userinfo' => $this->getUser(), 'errors' => $errors));
            }
            $em = $doctrine->getManager();
            $em->flush();
            $em->clear();


            return $this->redirectToRoute('app_settings', array('userinfo' => $this->getUser(),'passwordmatch'=>$match));
        }
    }

    #[Route('/email', name: 'app_email')]
    public function sendEmail(Request $request,MailerInterface $mailer,UserRepository $repo): Response
    {
        if ($request->getMethod() === 'POST'){
            $request->request->all();
            $inputs = $request->request->all();
            $user=$repo->findBy(['email' => $inputs["email"]]);
            if($user){
                $email = (new TemplatedEmail())
                ->from('aziz.rezgui@esprit.tn')
                ->to($inputs["email"])
                //->cc('cc@example.com')
                //->bcc('bcc@example.com')
                //->replyTo('fabien@example.com')
                //->priority(Email::PRIORITY_HIGH)
                ->htmlTemplate('mailer/mailer.html.twig')
                ->context([
                    'pass' => $user[0]->getPassword(),
                ]);
                $mailer->send($email);
                return $this->redirectToRoute('app_login');
            }
        // ...
        }
        return $this->render('user/forgotPassword.html.twig');
    }
    #[Route('/user/settings', name: 'app_settings')]
    public function indexsettings(): Response
    {
        $errors=null;
        return $this->render('user/settings.html.twig',array('userinfo'=>$this->getUser(),'errors' => $errors));
    }
    #[Route('/user/ForgotPassword', name: 'app_ForgotPassword')]
    public function indexforgotpass(): Response
    {
        return $this->render('user/forgotPassword.html.twig');
    }


    #[Route('/user/{id}', name: 'app_user')]
    public function index($id,CoachRepository $coachRepository): Response
    {
    $coach = $coachRepository->find($id);
    
    dump($coach);    
        return $this->render('user/index.html.twig', array(
        'coach' => $coach
        
    ));
    }
    #[Route('/banned', name: 'app_banned')]
    public function bannedindex(): Response
    {
        return $this->render('banned/banned.html.twig');
    }
}
