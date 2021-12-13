<?php

namespace App\Controller;

use App\Entity\Movie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class AllMoviesController extends AbstractController
{
    #[Route('/all/movies', name: 'all_movies')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $movieRepo = $doctrine->getRepository(Movie::class);
        $movies = $movieRepo->findAll();
        
        return $this->render('all_movies/index.html.twig', [
            'movies' => $movies
        ]);
    }
}
