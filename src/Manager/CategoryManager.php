<?php


namespace App\Manager;


use App\Entity\Category;
use App\Repository\CategoryRepository;

class CategoryManager
{
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function getCategoryByTitle(string $title) : ?Category
    {
        return $this->categoryRepository->findOneBy(['title' => $title]);
    }

}