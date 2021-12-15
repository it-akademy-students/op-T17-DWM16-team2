<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 */
class Order
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Movie::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $movie;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $reference;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $stripeToken;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $brandStripe;

    /**
     * @ORM\Column(type="integer")
     */
    private $last4Stripe;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $idChargeStripe;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $statusStripe;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getMovie(): ?Movie
    {
        return $this->movie;
    }

    public function setMovie(?Movie $movie): self
    {
        $this->movie = $movie;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getStripeToken(): ?string
    {
        return $this->stripeToken;
    }

    public function setStripeToken(string $stripeToken): self
    {
        $this->stripeToken = $stripeToken;

        return $this;
    }

    public function getBrandStripe(): ?string
    {
        return $this->brandStripe;
    }

    public function setBrandStripe(string $brandStripe): self
    {
        $this->brandStripe = $brandStripe;

        return $this;
    }

    public function getLast4Stripe(): ?int
    {
        return $this->last4Stripe;
    }

    public function setLast4Stripe(int $last4Stripe): self
    {
        $this->last4Stripe = $last4Stripe;

        return $this;
    }

    public function getIdChargeStripe(): ?string
    {
        return $this->idChargeStripe;
    }

    public function setIdChargeStripe(string $idChargeStripe): self
    {
        $this->idChargeStripe = $idChargeStripe;

        return $this;
    }

    public function getStatusStripe(): ?string
    {
        return $this->statusStripe;
    }

    public function setStatusStripe(string $statusStripe): self
    {
        $this->statusStripe = $statusStripe;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }
}
