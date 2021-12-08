<?php

namespace App\Entity;

use App\Repository\MovieRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MovieRepository::class)
 */
class Movie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $imdb_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImdbId(): ?int
    {
        return $this->imdb_id;
    }

    public function setImdbId(int $imdb_id): self
    {
        $this->imdb_id = $imdb_id;

        return $this;
    }
}
