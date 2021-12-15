<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Manager\MovieManager;
use App\Entity\Movie;
use App\Service\CartService;

class CheckoutController extends AbstractController
{
    #[Route('/checkout', name: 'checkout')]
    public function index(CartService $cartService, MovieManager $movieManager): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
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

        $movies = [];
        foreach ($cartService->getCart()['items'] as $item) {
            $movies[] = $item['movie']['movie']->getId();
        }

        $movies = implode(',', $movies);

        if ($request->getMethod() === 'POST') {
            $resource = $movieManager->stripe($_POST, $movies);

            if (null !== $resource) {
                $movieManager->createOrder($resource, $movies, $user, $cartService->getCart()['total']);
                $session->remove('panier');
                return $this->render('payment/success.html.twig', [
                    'cart' => $cartService
                ]);
            }
        }

        return $this->redirectToRoute('checkout');
    }
}
