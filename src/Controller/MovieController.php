<?php

namespace App\Controller;

use App\Entity\Movie;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
    #[Route('/movie/{id}', name: 'movie')]
    public function index(ManagerRegistry $doctrine, int $id): Response
    {
        $movie = $doctrine->getRepository(Movie::class)->find($id);

        if (!$movie) {
            throw $this->createNotFoundException(
                'No movie found for id '.$id
            );
        }

        return $this->render('movie/index.html.twig', [
            'movie' => $movie,
        ]);
    }
}
