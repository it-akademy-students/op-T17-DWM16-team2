<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Order;
use App\Service\CallApiService;
use App\Repository\MovieRepository;

class OrderController extends AbstractController
{
    #[Route('/order/{reference}', name: 'order')]
    public function index(Order $order, CallApiService $callApiService, MovieRepository $movieRepository): Response
    {
        // Si l'utilisateur n'est pas connecté ou alors que ce n'est pas sa commande, on le redirige
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        } elseif ($this->getUser() !== $order->getUser()) {
            return $this->redirectToRoute('account');
        }

        $moviesId = explode(',', $order->getMovie());

        $movies = [];
        foreach ($moviesId as $movieId) {
            $movies[] = [
                'db' => $movieRepository->find($movieId),
                $callApiService->getMovieData($movieRepository->find($movieId)->getImdbId())
                // 0 => [
                //     'Title' => 'Inception',
                //     'Poster' => 'https://imdb-api.com/images/original/MV5BMjAxMzY3NjcxNF5BMl5BanBnXkFtZTcwNTI5OTM0Mw@@._V1_Ratio0.6762_AL_.jpg'
                // ]
            ];
        }
        
        return $this->render('order/index.html.twig', [
            'order' => $order,
            'movies' => $movies
        ]);
    }
}
