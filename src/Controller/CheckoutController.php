<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CheckoutController extends AbstractController
{
    #[Route('/checkout', name: 'checkout')]
    public function index(): Response
    {
        \Stripe\Stripe::setApiKey('sk_test_51K6Z6RH14CdCbTSSb959s24mEvJob5RrRSNP11YAmvrSWFha9AZJzbRyE0qw9DoGDkrLlOuuTUnucgDzQGePXdqM00QoLXF6H1');

        return $this->render('checkout/index.html.twig');
    }

    #[Route('/checkout/create', name: 'checkout_create')]
    public function checkoutCreate(): String
    {
        function calculateOrderAmount(array $items): int
        {
            // Replace this constant with a calculation of the order's amount
            // Calculate the order total on the server to prevent
            // people from directly manipulating the amount on the client
            return 1400;
        }

        \Stripe\Stripe::setApiKey('sk_test_51K6Z6RH14CdCbTSSb959s24mEvJob5RrRSNP11YAmvrSWFha9AZJzbRyE0qw9DoGDkrLlOuuTUnucgDzQGePXdqM00QoLXF6H1');

        // retrieve JSON from POST body
        $jsonStr = file_get_contents('php://input');
        $jsonObj = json_decode($jsonStr);

        // Create a PaymentIntent with amount and currency
        $paymentIntent = \Stripe\PaymentIntent::create([
            'amount' => calculateOrderAmount($jsonObj->items),
            'currency' => 'eur',
            'automatic_payment_methods' => [
                'enabled' => true,
            ],
        ]);

        $output = [
            'clientSecret' => $paymentIntent->client_secret,
        ];

        return json_encode($output);
    }
}
