<?php

namespace App\Controller;

use App\Repository\TutorialRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TutorialController extends AbstractController
{
    /*#[Route('/tutoriels/{page?1}', name: 'app_tutorials')]
    public function tutorials($page ,TutorialRepository $tutorialRepository, CategoryRepository $categoryRepository): Response
    {
        $result = $tutorialRepository->paginate($page, 1);
        $tutorials =$result['data'];
        $totalPages = ceil($result['total'] / 1);
        $categories = $categoryRepository->findAll();

        return $this->render('tutorial/tutorials1.html.twig', [
            'title' => 'Tutoriels',
            'tutorials' => $tutorials,
            'categories' => $categories,
            'currentPage' => $page,
            'totalPages' => $totalPages
        ]);
    }*/

    #[Route('/tutoriels/{slug?}', name: 'app_tutorials')]
    public function tutorials(Request $request, TutorialRepository $repo, CategoryRepository $categoryRepository, string $slug = null)
    {
        $page = $request->query->getInt('page', 1); // pagination
        $limit = 10;

        // Pagination + filtre catégorie
        $result = $repo->paginateByCategory($slug, $page, $limit);
        $categories = $categoryRepository->findAll();

        return $this->render('tutorial/tutorials.html.twig', [
            'title' => 'Tutoriels',    
            'tutorials' => $result['data'],
            'currentPage' => $page,
            'totalPages' => ceil($result['total'] / $limit),
            'categorySlug' => $slug,
            'categories' => $categories,
        ]);
    }
    /*
    #[Route('/tutoriels/{slug}', name: 'app_tutorial_category')]
    public function tutorialByCategory($slug, CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->findOneBySlug($slug);
        $categories = $categoryRepository->findAll();

        return $this->render('tutorial/tutorials1.html.twig', [
            'title' => 'Tutoriels',
            'category' => $category,
            'categories' => $categories
        ]);
    }
*/
    #[Route('/tutoriel/{slug}', name: 'app_tutorial')]
    public function tutorial($slug, TutorialRepository $tutorialRepository): Response
    {
        $tutorial = $tutorialRepository->findOneBySlug($slug);

        return $this->render('tutorial/tutorial.html.twig', [
            'title' => 'Tutoriel',
            'tutorial' => $tutorial,
            'similar_tutorials' => []
        ]);
    }

    
}
