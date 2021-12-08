<?php

namespace App\Controller;

use App\Entity\Movie;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\CallApiService;

class MovieController extends AbstractController
{
    #[Route('/movie/{id}', name: 'movie')]
    public function index(ManagerRegistry $doctrine, int $id, CallApiService $callApiService): Response
    {
        $movie = $doctrine->getRepository(Movie::class)->find($id);

        if (!$movie) {
            // Si le film n'est pas trouvé, alors on redirige vers la page d'accueil
            return $this->redirectToRoute('home');
        }

        $movie = $callApiService->getMovieData($movie->getImdbId());

        return $this->render('movie/index.html.twig', [
            'movie' => $movie,
        ]);
    }
}
