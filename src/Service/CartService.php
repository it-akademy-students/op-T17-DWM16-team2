<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\MovieRepository;

class CartService
{

    public function __construct(SessionInterface $session, MovieRepository $movieRepository)
    {
        $this->session = $session;
        $this->movieRepository = $movieRepository;
    }

    public function getCart(): array
    {
        $panier = $this->session->get('panier');

        $panierWithData = [];

        if ($panier !== null) {
            foreach ($panier as $id => $quantity) {
                $panierWithData[] = [
                    'movie' => [
                        'movie' => $this->movieRepository->find($id),
                        0 => [
                            'title' => 'Inception',
                            'image' => 'https://imdb-api.com/images/original/MV5BMjAxMzY3NjcxNF5BMl5BanBnXkFtZTcwNTI5OTM0Mw@@._V1_Ratio0.6762_AL_.jpg'
                        ]
                    ],
                    'quantity' => $quantity
                ];
            }
        }

        $total = 0;

        foreach ($panierWithData as $item) {
            $totalItem = $item['movie']['movie']->getPrice() * $item['quantity'];
            $total += $totalItem;
        }

        return [
            'items' => $panierWithData,
            'total' => $total
        ];
    }
}
