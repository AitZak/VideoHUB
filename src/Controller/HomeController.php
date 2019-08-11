<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\UserRepository;
use App\Repository\VideoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(VideoRepository $videoRepository, Security $security)
    {

        return $this->render('home/index.html.twig', [
            'videos' => $videoRepository->findBy(['published' => true], ['title' => 'ASC']),
            'userLogged' => $security->getUser(),
        ]);
    }
}
