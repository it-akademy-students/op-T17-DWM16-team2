<?php

namespace App\Service;
use App\Entity\Movie;
use App\Service\CartService;

class StripeService
{
    private $privateKey;
    private $cartService;

    public function __construct(CartService $cartService)
    {
        if ($_ENV['APP_ENV'] === 'dev') {
            $this->privateKey = $_ENV['STRIPE_SECRETE_KEY_TEST'];
        } else {
            $this->privateKey = $_ENV['STRIPE_SECRETE_KEY_LIVE'];
        }
        $this->cartService = $cartService;
    }

    public function paymentIntent()
    {
        \Stripe\Stripe::setApiKey($this->privateKey);
        return \Stripe\PaymentIntent::create([
            'amount' => $this->cartService->getCart()['total'] * 100,
            'currency' => 'eur',
            'payment_method_types' => ['card']
        ]);
    }

    public function payment($amount, $currency, $description, Array $stripeParameter)
    {
        \Stripe\Stripe::setApiKey($this->privateKey);
        $paymentIntent = null;

        if (isset($stripeParameter['stripeIntentId'])) {
            $paymentIntent = \Stripe\PaymentIntent::retrieve($stripeParameter['stripeIntentId']);
        }

        if ($stripeParameter['stripeIntentStatus'] === 'succeeded') {
            // TODO
        } else {
            $paymentIntent->cancel();
        }

        return $paymentIntent;
    }

    public function stripe(Array $stripeParameter, Movie $movie)
    {
        return $this->payment($movie->getPrice() * 100, 'eur', $movie->getImdbId(), $stripeParameter);
    }
}