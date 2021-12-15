<?php

namespace App\Manager;

use App\Entity\Movie;
use App\Entity\User;
use App\Entity\Order;
use App\Service\StripeService;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class MovieManager
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    protected $stripeService;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager, StripeService $stripeService) {
        $this->em = $entityManager;
        $this->stripeService = $stripeService;
    }

    public function getMovies()
    {
        return $this->em->getRepository(Movie::class)
            ->findAll();
    }

    public function intentSecret(Movie $movie)
    {
        $intent = $this->stripeService->paymentIntent($movie);
        return $intent['client_secret'] ?? null;
    }

    public function stripe(array $stripeParameter, Movie $movie)
    {
        $resource = null;
        $data = $this->stripeService->stripe($stripeParameter, $movie);

        if ($data) {
            $resource = [
                'stripeBrand' => $data['charges']['data'][0]['payment_method_details']['card']['brand'],
                'stripeLast4' => $data['charges']['data'][0]['payment_method_details']['card']['last4'],
                'stripeId' => $data['charges']['data'][0]['id'],
                'stripeStatus' => $data['charges']['data'][0]['token'],
                'stripeToken' => $data['client_secret'],
            ];
        }

        return $resource;
    }

    public function createOrder(array $resource, Movie $movie, User $user)
    {
        $order = new Order();
        $order->setUser($user)
            ->setMovie($movie)
            ->setPrice($movie->getPrice())
            ->setReference(uniqid())
            ->setBrandStripe($resource['stripeBrand'])
            ->setLast4Stripe($resource['stripeLast4'])
            ->setIdChargeStripe($resource['stripeId'])
            ->setStatusStripe($resource['stripeStatus'])
            ->setStripeToken($resource['stripeToken'])
            ->setUpdatedAt(new \DateTime())
            ->setCreatedAt(new \DateTime());
        $this->em->persist($order);
        $this->em->flush();
    }
}
