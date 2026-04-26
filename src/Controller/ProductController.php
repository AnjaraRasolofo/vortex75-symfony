<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProductController extends AbstractController
{
    #[Route('/experts', name: 'app_experts', methods: ['GET'])]
    public function index(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();

        return $this->render('product/products.html.twig', [
            'title' => 'Experts et Indicateurs',
            'products' => $products
        ]);
    }

    #[Route('/experts/catalogue', name: 'app_catalogue', methods: ['GET'])]
    public function showCatalogue( Request $request, ProductRepository $productRepository): Response
    {
        //$products = $productRepository->findAll();
        $type = $request->query->get('type');
        $price = $request->query->get('price');

        $price = ($price !== null && $price !== '') ? (float) $price : null;

        $products = $productRepository->findByFilters($type, $price);

        $products = $productRepository->findByFilters($type, $price);

        return $this->render('product/catalogue.html.twig', [
            'title' => 'Catalogue',
            'products' => $products,
            'type' => $type,
            'price' => $price
        ]);
    }
    
    #[Route('/expert/{id}', name: 'app_expert', methods: ['GET'])]
    public function show($id, ProductRepository $productRepository): Response
    {
        $product = $productRepository->findOneById($id);

        return $this->render('product/product.html.twig', [
            'title' => 'Affiher un Produit',
            'product' => $product
        ]);
    }

    #[Route('/experts/shopping-cart/add/{id}', name: 'cart_add')]
    public function add(int $id, Request $request, ProductRepository $repo): Response {
        $session = $request->getSession();
        $cart = $session->get('shoppingCart', []);

        $product = $repo->find($id);

        if (!$product) {
            return $this->redirectToRoute('experts_catalogue');
        }

        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                'name' => $product->getName(),
                'price' => $product->getPrice(),
                'image' => $product->getImage(),
                'quantity' => 1
            ];
        }

        $session->set('shoppingCart', $cart);

        $this->addFlash('success', $product->getName() . ' ajouté au panier.');

        return $this->redirectToRoute('app_expert', ['id' => $id]);
    }
    
    #[Route('/experts/shopping-cart', name: 'shopping_cart')]
    public function showShoppingCart(Request $request): Response
    {
        $session = $request->getSession();
        $cart = $session->get('shoppingCart', []);

        return $this->render('product/shopping-cart.html.twig', [
            'shoppingCart' => $cart,
            'success' => $this->addFlash('success', null),
        ]);
    }

    #[Route('/experts/shopping-cart/delete/{id}', name: 'cart_delete')]
    public function delete(int $id, Request $request): Response
    {
        $session = $request->getSession();
        $cart = $session->get('shoppingCart', []);

        if (!isset($cart[$id])) {
            throw $this->createNotFoundException('Produit non trouvé dans le panier');
        }

        unset($cart[$id]);

        $session->set('shoppingCart', $cart);

        return $this->redirectToRoute('shopping_cart');
    }

    #[Route('/experts/shopping-cart/reset', name: 'cart_reset', methods: ['POST'])]
    public function reset(Request $request): Response
    {
        // Vérification CSRF
        if (!$this->isCsrfTokenValid('reset_cart', $request->request->get('_token'))) {
            throw $this->createAccessDeniedException();
        }

        $session = $request->getSession();
        $session->remove('shoppingCart');

        $this->addFlash('success', 'Panier vidé avec succès.');

        return $this->redirectToRoute('shopping_cart');
    }
}
