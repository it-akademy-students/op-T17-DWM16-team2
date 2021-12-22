<?php

namespace App\Service;

use App\Repository\MovieRepository;
use App\Service\CallApiService;
use Symfony\Component\HttpFoundation\RequestStack;

class CartService
{
    public function __construct(RequestStack $requestStack, MovieRepository $movieRepository, CallApiService $callApiService)
    {
        $this->session = $requestStack->getSession();
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

    public function add($id)
    {
        $cart = $this->session->get('cart');

        if (!empty($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }

        $this->session->set('cart', $cart);
    }

    public function delete($id)
    {
        $cart = $this->session->get('cart');

        if (!empty($cart[$id])) {
            unset($cart[$id]);
        }

        $this->session->set('cart', $cart);
    }

    public function remove($id)
    {
        $cart = $this->session->get('cart');

        if (!empty($cart[$id])) {
            if ($cart[$id] > 1) {
                $cart[$id]--;
            } else {
                unset($cart[$id]);
            }
        }

        $this->session->set('cart', $cart);
    }
}
