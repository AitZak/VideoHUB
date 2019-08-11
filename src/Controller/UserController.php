<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Video;
use App\Form\ProfileUserType;
use App\Form\RegisterUserType;
use App\Manager\UserManager;
use App\Repository\UserRepository;
use App\Repository\VideoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;

class UserController extends AbstractController
{
    /**
     * @Route("/users", name="users")
     */
    public function index(Request $request, UserRepository $userRepository, EntityManagerInterface $em,UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();
        $form = $this->createForm(RegisterUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $password = $passwordEncoder->encodePassword($user,$user->getPassword());
            $user->setPassword($password);
            $em->persist($user);
            $em->flush();
            $this->addFlash('notice', 'Your registred a new user.');
        }

        return $this->render('user/index.html.twig', [
            'form' => $form->createView(),
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/profile/{id}", name="profile")
     */
    public function profile(Request $request, UserRepository $userRepository, EntityManagerInterface $em, User $user, Security $security)
    {
        $form = $this->createForm(ProfileUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $em->persist($user);
            $em->flush();
            $this->addFlash('notice', 'Your changes were saved.');
            return $this->redirectToRoute('home');
        }

        return $this->render('user/profile.html.twig', [
            'form' => $form->createView(),
            'users' => $userRepository->findBy(['id'=> $user->getId()]),
            'userLogged' => $security->getUser(),
        ]);
    }


    /**
     * @Route("/user/videos/{id}", name="user_video")
     */
    public function userVideos(Request $request, VideoRepository $videoRepository, EntityManagerInterface $em, Security $security)
    {
        $user = $security->getUser();
        return $this->render('user/video.html.twig', [
            'videos' => $videoRepository->findBy(['user' => $user], ['title' =>'ASC']),
        ]);
    }

    /**
     * @Route("/user/remove/{id}", name="user_remove")
     */
    public function remove(User $user, EntityManagerInterface $entityManager)
    {
        $videos = $user->getVideos();
        foreach ($videos as $video){
            $video->setUser(null);
        }

        $entityManager->remove($user);
        $entityManager->flush();
        $this->addFlash('notice', 'An user has been deleted ');
        return $this->redirectToRoute('category');
    }
}
