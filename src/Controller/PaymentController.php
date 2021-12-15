<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Service\CartService;

class PaymentController extends AbstractController
{
    #[Route('/payment', name: 'payment')]
    public function payment(SessionInterface $session, CartService $cart): Response
    {
        $cartItems = $cart->getCart();

        $products = [];
        foreach ($cartItems['items'] as $item) {
            $products[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $item['movie'][0]['title'],
                    ],
                    'unit_amount' => $item['movie']['movie']->getPrice() * 100,
                ],
                'quantity' => $item['quantity'],
            ];
        }
        
        \Stripe\Stripe::setApiKey($_ENV['STRIPE_SECRETE_KEY_TEST']);

        $session = \Stripe\Checkout\Session::create([
            'line_items' => [
                $products
            ],
            'mode' => 'payment',
            'success_url' => $this->generateUrl('success_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generateUrl('cart_index', [], UrlGeneratorInterface::ABSOLUTE_URL)
        ]);

        return $this->redirect($session->url, 303);
    }

    #[Route('/success', name: 'success_url')]
    public function success(): Response
    {
        return $this->render('payment/success.html.twig');
    }
}
