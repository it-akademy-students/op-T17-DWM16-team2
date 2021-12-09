<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Entity\MovieFavorite;
use App\Repository\MovieFavoriteRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\CallApiService;
use Doctrine\Persistence\ObjectManager;

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

        // $movie = $callApiService->getMovieData($movie->getImdbId());
        $movieData = [
            'movie' => $movie,
            0 => [
                'title' => 'Inception',
                'year' => 2021,
                'plotLocal' => 'Dom Cobb est un voleur expérimenté, le meilleur dans l\'art dangereux de l\'extraction, voler les secrets les plus intimes enfouis au plus profond du subconscient durant une phase de rêve, lorsque l\'esprit est le plus vulnérable. Les capacités de Cobb ont fait des envieux dans le monde tourmenté de l\'espionnage industriel alors qu\'il devient fugitif en perdant tout ce qu\'il a un jour aimé. Une chance de se racheter lui est alors offerte. Une ultime mission grâce à laquelle il pourrait retrouver sa vie passée mais uniquement s\'il parvient à accomplir l\'impossible inception.',
                'imDbRating' => 8.3,
                'image' => 'https://imdb-api.com/images/original/MV5BMjAxMzY3NjcxNF5BMl5BanBnXkFtZTcwNTI5OTM0Mw@@._V1_Ratio0.6762_AL_.jpg'
            ]
        ];
        
        return $this->render('movie/index.html.twig', [
            'movie' => $movieData,
        ]);
    }

    #[Route('/movie/{id}/favorite', name: 'movie_favorite')]
    public function favorite(Movie $movie, EntityManagerInterface $entityManager, MovieFavoriteRepository $FavoriteRepo): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->json([
                'status' => 'unauthorized',
                'message' => 'Utilisateur non connecté.'
            ], 403);
        }

        if ($movie->isFavoritedByUser($user)) {
            $favorite = $FavoriteRepo->findOneBy([
                'movie' => $movie,
                'user' => $user
            ]);
            $entityManager->remove($favorite);
            $entityManager->flush();

            return $this->json([
                'status' => 'success',
                'isFavorited' => false
            ]);
        }

        $favorite = new MovieFavorite();
        $favorite->setMovie($movie);
        $favorite->setUser($user);
        $favorite->setDate(new \DateTime(date('Y-m-d H:i:s')));
        
        $entityManager->persist($favorite);
        $entityManager->flush();

        return $this->json([
            'status' => 'success',
            'isFavorited' => true
        ]);
    }
}
