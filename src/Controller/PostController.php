<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PostController extends AbstractController
{
    #[Route('/actualites/{slug}', name: 'app_post')]
    public function index($slug, PostRepository $postRepository): Response
    {
        $post = $postRepository->findOneBySlug($slug);

        return $this->render('post/index.html.twig', [
            'title' => 'Actualités',
            'post' => $post,
            'similar_posts' => []
        ]);
    }
}
