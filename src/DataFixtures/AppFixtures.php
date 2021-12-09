<?php

namespace App\DataFixtures;

use App\Entity\Movie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $imdb_ids = [
            'tt3315342',
            'tt4154796',
            'tt4633694',
            'tt8108198',
            'tt2380307',
            'tt3170832',
            'tt2582802',
            'tt1954470',
            'tt1255953',
            'tt1375666'
        ];

        foreach ($imdb_ids as $imdb_id) {
            $movie = new Movie();
            $movie->setImdbId($imdb_id);
            $movie->setPrice(9.99);
            $manager->persist($movie);
        }

        $manager->flush();
    }
}
