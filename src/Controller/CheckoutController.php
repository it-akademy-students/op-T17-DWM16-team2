<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Manager\MovieManager;
use App\Service\CartService;

class CheckoutController extends AbstractController
{
    #[Route('/checkout', name: 'checkout')]
    public function index(CartService $cartService, MovieManager $movieManager): Response
    {
        // Si l'utilisateur n'est pas connecté ou que le panier est vide, alors on le redirige
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        } else if (count($cartService->getCart()['items']) < 1) {
            return $this->redirectToRoute('cart_index');
        }

        return $this->render('checkout/index.html.twig', [
            'cart' => $cartService->getCart(),
            'intentSecret' => $movieManager->intentSecret()
        ]);
    }

    #[Route('/checkout/pay', name: 'checkout_pay')]
    function purchase(CartService $cartService, Request $request, MovieManager $movieManager, SessionInterface $session)
    {
        $user = $this->getUser();

        // Si l'utilisateur n'est pas connecté ou que le panier est vide, alors on le redirige
        if (!$user || count($cartService->getCart()['items']) < 1) {
            return $this->redirectToRoute('cart_index');
        }

        foreach ($cartService->getCart()['items'] as $item) {
            $movies[] = $item['movie']['db']->getId();
        }

        $movies = implode(',', $movies);

        if ($request->getMethod() === 'POST') {
            $resource = $movieManager->stripe($_POST, $movies);

            if (null !== $resource) {
                $order = $movieManager->createOrder($resource, $movies, $user, $cartService->getCart()['total']);
                $session->remove('cart');
                return $this->render('checkout/success.html.twig', [
                    'order' => $order
                ]);
            }
        }

        return $this->redirectToRoute('checkout');
    }
}
