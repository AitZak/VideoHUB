<?php

namespace App\Controller;

use App\Entity\Video;
use App\Form\VideoType;
use App\Repository\VideoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class VideoController extends AbstractController
{
    /**
     * @Route("/videos", name="videos")
     */
    public function index(Request $request, VideoRepository $videoRepository, EntityManagerInterface $em, Security $security)
    {
        $video = new Video();
        $video->setUser($security->getUser());
        $form= $this->createForm(VideoType::class, $video);
        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid()){
            $em->persist($video);
            $em->flush();
        }

        return $this->render('video/index.html.twig', [
            'form' => $form->createView(),
            'videos' => $videoRepository->findAll(),
        ]);
    }
    /**
     * @Route("/video/create", name="video_create")
     */
    public function videoCreate(Request $request, VideoRepository $videoRepository, EntityManagerInterface $em, Security $security,LoggerInterface $logger)
    {
        $video = new Video();
        $video->setUser($security->getUser());
        $form= $this->createForm(VideoType::class, $video);
        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid()){
            $em->persist($video);
            $em->flush();
            $this->addFlash('notice', 'An User have been created');
            $logger->info('Video have been created: created by '.$video->getUser()->getEmail().', the title was '.$video->getTitle().', and the id was '.$video->getId());
        }

        return $this->render('video/create.html.twig', [
            'form' => $form->createView(),
            'videos' => $videoRepository->findAll(),
        ]);
    }

    /**
     * @Route("/video/{id}", name="video_details")
     */
    public function videoDetails(Request $request, VideoRepository $videoRepository, EntityManagerInterface $em, Video $video)
    {
        $form= $this->createForm(VideoType::class, $video);
        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid()){
            $em->persist($video);
            $em->flush();
        }

        return $this->render('video/details.html.twig', [
            'form' => $form->createView(),
            'videos' => $videoRepository->findBy(['id' => $video->getId()]),
        ]);
    }

    /**
     * @Route("/video/edit/{id}", name="video_edit")
     */
    public function videoEdit(Request $request, VideoRepository $videoRepository, EntityManagerInterface $em, Video $video, Security $security, LoggerInterface $logger)
    {
        $form= $this->createForm(VideoType::class, $video);
        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid()){
            $em->persist($video);
            $em->flush();
            $this->addFlash('notice','Your changes were saved');
            $logger->info('Video have been edited: created by '.$video->getUser()->getEmail().', the title was '.$video->getTitle().', and the id was '.$video->getId());
            return $this->redirectToRoute('home');
        }

        return $this->render('video/edit.html.twig', [
            'form' => $form->createView(),
            'videos' => $videoRepository->findBy(['id' => $video->getId()]),
            'userLogged' => $security->getUser(),
        ]);
    }

    /**
     * @Route("/video/remove/{id}", name="video_remove")
     */
    public function remove(Video $video, EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        $entityManager->remove($video);
        $entityManager->flush();
        $this->addFlash('notice','A video has been deleted.');
        $logger->info('Video have been deleted: created by '.$video->getUser()->getEmail().', the title was '.$video->getTitle().', and the id was '.$video->getId());
        return $this->redirectToRoute('video');
    }
}
