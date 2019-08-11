<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/categories", name="categories")
     */
    public function index(Request $request, CategoryRepository $categoryRepository, EntityManagerInterface $em)
    {
        $category = new Category();
        $form= $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid()){
            $em->persist($category);
            $em->flush();
        }

        return $this->render('category/index.html.twig', [
            'form' => $form->createView(),
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/category/create", name="category_create")
     */
    public function create(Request $request, EntityManagerInterface $em)
    {
        $category = new Category();
        $form= $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid()){
            $em->persist($category);
            $em->flush();
            $this->addFlash('notice', 'A new category have been created');
            return $this->redirectToRoute('category');
        }

        return $this->render('category/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/category/edit/{id}", name="category_edit")
     */
    public function edit(Request $request, EntityManagerInterface $em, CategoryRepository $categoryRepository, Category $category)
    {
        $form= $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid()){
            $em->persist($category);
            $em->flush();
            $this->addFlash('notice', 'Your changes were saved');
            return $this->redirectToRoute('category');
        }

        return $this->render('category/edit.html.twig', [
            'form' => $form->createView(),
            'categories'=> $categoryRepository->findBy(['id'=>$category->getId()])
        ]);
    }

    /**
     * @Route("/category/{title}", name="category_videos")
     */
    public  function categoryVideos(CategoryRepository $categoryRepository, Category $category){
        $title = $category->getTitle();

        return $this->render('category/category_videos.html.twig', [
            'categories' => $categoryRepository->findBy(['title'=> $title]),
        ]);
    }

    /**
     * @Route("/category/remove/{id}", name="category_remove")
     */
    public function remove(Category $category, EntityManagerInterface $entityManager)
    {
        $videos = $category->getVideos();
        foreach ($videos as $video){
            $video->setCategory(null);
        }
        $entityManager->remove($category);
        $entityManager->flush();
        $this->addFlash('notice','A category has been deleted');
        return $this->redirectToRoute('category');
    }
}
