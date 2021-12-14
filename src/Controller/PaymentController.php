<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PaymentController extends AbstractController
{
    #[Route('/payment', name: 'payment')]
    public function index(): Response
    {
        return $this->render('payment/index.html.twig', [
            'controller_name' => 'PaymentController',
        ]);
    }

    #[Route('/checkout', name: 'checkout')]
    public function checkout(SessionInterface $session): Response
    {

        dd($session->get('panier', []));


        \Stripe\Stripe::setApiKey('sk_test_51K6Z6RH14CdCbTSSb959s24mEvJob5RrRSNP11YAmvrSWFha9AZJzbRyE0qw9DoGDkrLlOuuTUnucgDzQGePXdqM00QoLXF6H1');

        $session = \Stripe\Checkout\Session::create([
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => 'T-shirt',
                        ],
                        'unit_amount' => 999,
                    ],
                    'quantity' => 1,
                ],
                [
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => 'T-shirt',
                        ],
                        'unit_amount' => 999,
                    ],
                    'quantity' => 1,
                ]
            ],
            'mode' => 'payment',
            'success_url' => $this->generateUrl('success_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generateUrl('cancel_url', [], UrlGeneratorInterface::ABSOLUTE_URL)
        ]);

        return $this->redirect($session->url, 303);
    }

    #[Route('/success', name: 'success_url')]
    public function success(): Response
    {
        return $this->render('payment/success.html.twig');
    }

    #[Route('/cancel', name: 'cancel_url')]
    public function cancel(): Response
    {
        return $this->render('payment/cancel.html.twig');
    }
}
