<?php

namespace App\Service;
use App\Entity\Movie;

class StripeService
{
    private $privateKey;

    public function __construct()
    {
        if ($_ENV['APP_ENV'] === 'dev') {
            $this->privateKey = $_ENV['STRIPE_PRIVATE_KEY_TEST'];
        } else {
            $this->privateKey = $_ENV['STRIPE_PRIVATE_KEY_LIVE'];
        }
    }

    public function paymentIntent(Movie $movie)
    {
        \Stripe\Stripe::setApiKey($this->privateKey);
        return \Stripe\paymentIntent::create([
            'amount' => $movie->getPrice() * 100,
            'current' => 'eur',
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

        if ($stripeParameter['stripeIntentId'] === 'succeeded') {
            //TODO
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