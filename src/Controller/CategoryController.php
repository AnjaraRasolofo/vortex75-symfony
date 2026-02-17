<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CategoryController extends AbstractController
{
    #[Route('/categorie/{slug}', name: 'app_category')]
    public function index($slug, CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->findOneBySlug($slug);
        $categories = $categoryRepository->findAll();

        return $this->render('category/index.html.twig', [
            'title' => 'Categorie',
            'category' => $category,
            'categories' => $categories
        ]);
    }

     #[Route('/categorie', name: 'app_all_category')]
    public function posts($slug, PostRepository $postRepository, CategoryRepository $categoryRepository): Response
    {
        $posts = $postRepository->findAll();
        $categories = $categoryRepository->findAll();

        return $this->render('category/index.html.twig', [
            'title' => 'Actualités',
            'posts' => $posts,
            'categories' => $categories
        ]);
    }

}
