<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\MovieRepository;
use App\Service\CallApiService;

class CartService
{
    public function __construct(SessionInterface $session, MovieRepository $movieRepository, CallApiService $callApiService)
    {
        $this->session = $session;
        $this->movieRepository = $movieRepository;
        $this->callApiService = $callApiService;
    }

    public function getCart(): array
    {
        $cart = $this->session->get('cart');
        $cartData = [];

        if ($cart !== null) {
            foreach ($cart as $id => $quantity) {
                $movie = $this->movieRepository->find($id);
                $cartData[] = [
                    'movie' => [
                        'db' => $movie,
                        $this->callApiService->getMovieData($movie->getImdbId())
                        // 0 => [
                        //     'title' => 'Inception',
                        //     'image' => 'https://imdb-api.com/images/original/MV5BMjAxMzY3NjcxNF5BMl5BanBnXkFtZTcwNTI5OTM0Mw@@._V1_Ratio0.6762_AL_.jpg'
                        // ]
                    ],
                    'quantity' => $quantity
                ];
            }
        }

        $total = 0;

        foreach ($cartData as $item) {
            $totalItem = $item['movie']['db']->getPrice() * $item['quantity'];
            $total += $totalItem;
        }

        return [
            'items' => $cartData,
            'total' => $total
        ];
    }
}
