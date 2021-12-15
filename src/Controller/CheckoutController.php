<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Manager\MovieManager;
use App\Entity\Movie;

class CheckoutController extends AbstractController
{
    #[Route('/checkout/{id}', name: 'checkout')]
    public function index(Movie $movie, MovieManager $movieManager): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('checkout/index.html.twig', [
            'movie' => $movie,
            'intentSecret' => $movieManager->intentSecret($movie)
        ]);
    }

    #[Route('/checkout/pay/{id}', name: 'checkout_pay')]
    function purchase(Movie $movie, Request $request, MovieManager $movieManager)
    {
        $user = $this->getUser();

        if ($request->getMethod() === 'POST') {
            $resource = $movieManager->stripe($_POST, $movie);

            if (null !== $resource) {
                $movieManager->createOrder($resource, $movie, $user);
                return $this->render('payment/success.html.twig', [
                    'movie' => $movie
                ]);
            }
        }

        return $this->redirectToRoute('checkout', ['id' => $movie->getId()]);
    }
}
