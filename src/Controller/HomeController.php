<?php

namespace App\Controller;

use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\CallApiService;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(MovieRepository $movieRepository, CallApiService $callApiService): Response
    {
        // Prend les x derniers films
        $movies = $movieRepository->findBy([], ['id' => 'ASC'], 4);

        foreach($movies as $movie) {
            $popularMovies[] = [
                'db' => $movie,
                $callApiService->getMovieData($movie->getImdbId())
            ];
            // array_push($popularMovies, [
            //     'id' => $movie->getId(),
            //     0 => [
            //         'title' => 'Inception',
            //         'year' => 2021,
            //         'plotLocal' => 'Dom Cobb est un voleur expérimenté, le meilleur dans l\'art dangereux de l\'extraction, voler les secrets les plus intimes enfouis au plus profond du subconscient durant une phase de rêve, lorsque l\'esprit est le plus vulnérable. Les capacités de Cobb ont fait des envieux dans le monde tourmenté de l\'espionnage industriel alors qu\'il devient fugitif en perdant tout ce qu\'il a un jour aimé. Une chance de se racheter lui est alors offerte. Une ultime mission grâce à laquelle il pourrait retrouver sa vie passée mais uniquement s\'il parvient à accomplir l\'impossible inception.',
            //         'imDbRating' => 8.3,
            //         'image' => 'https://imdb-api.com/images/original/MV5BMjAxMzY3NjcxNF5BMl5BanBnXkFtZTcwNTI5OTM0Mw@@._V1_Ratio0.6762_AL_.jpg'
            //     ]
            // ]);
        }
        return $this->render('home/index.html.twig', [
            'popularMovies' => $popularMovies
        ]);
    }
}
