<?php

namespace App\Controller;

use App\Repository\PostRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

final class PostController extends AbstractController
{
    #[Route('/actualites', name: 'app_posts')]
    public function posts(PostRepository $postRepository, CategoryRepository $categoryRepository): Response
    {
        $posts = $postRepository->findAll();
        $categories = $categoryRepository->findAll();

        return $this->render('post/posts.html.twig', [
            'title' => 'Actualités',
            'posts' => $posts,
            'categories' => $categories
        ]);
    }

    #[Route('/actualites/{slug}', name: 'app_post_category')]
    public function postsBycategory($slug, CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->findOneBySlug($slug);
        $categories = $categoryRepository->findAll();

        return $this->render('post/posts.html.twig', [
            'title' => 'Actualités par categorie',
            'category' => $category,
            'categories' => $categories
        ]);
    }

    #[Route('/actualite/{slug}', name: 'app_post')]
    public function index($slug, PostRepository $postRepository): Response
    {
        $post = $postRepository->findOneBySlug($slug);

        return $this->render('post/post.html.twig', [
            'title' => 'Actualités',
            'post' => $post,
            'similar_posts' => []
        ]);
    }
}
