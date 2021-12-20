<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\CallApiService;

class AccountController extends AbstractController
{
    #[Route('/account', name: 'account')]
    public function index(CallApiService $callApiService): Response
    {
        $user = $this->getUser();
        $favoriteMovies = [];
        $orders = [];
        
        foreach ($user->getMovieFavorites()->toArray() as $favoriteMovie) {
            $favoriteMovies[] = [
                'db' => $favoriteMovie->getMovie(),
                $callApiService->getMovieData($favoriteMovie->getMovie()->getImdbId())
                // 0 => [
                //     'Title' => 'Inception',
                //     'Year' => 2021,
                //     'Plot' => 'Dom Cobb est un voleur expérimenté, le meilleur dans l\'art dangereux de l\'extraction, voler les secrets les plus intimes enfouis au plus profond du subconscient durant une phase de rêve, lorsque l\'esprit est le plus vulnérable. Les capacités de Cobb ont fait des envieux dans le monde tourmenté de l\'espionnage industriel alors qu\'il devient fugitif en perdant tout ce qu\'il a un jour aimé. Une chance de se racheter lui est alors offerte. Une ultime mission grâce à laquelle il pourrait retrouver sa vie passée mais uniquement s\'il parvient à accomplir l\'impossible inception.',
                //     'imdbRating' => 8.3,
                //     'Poster' => 'https://imdb-api.com/images/original/MV5BMjAxMzY3NjcxNF5BMl5BanBnXkFtZTcwNTI5OTM0Mw@@._V1_Ratio0.6762_AL_.jpg'
                // ]
            ];
        }
        
        foreach ($user->getOrders()->toArray() as $order) {
            $orders[] = $order;
        }
        
        return $this->render('account/index.html.twig', [
            'user' => $user,
            'favorites' => $favoriteMovies,
            'orders' => $orders
        ]);
    }
}
