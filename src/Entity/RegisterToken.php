<?php

namespace App\Entity;

use App\Repository\RegisterTokenRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RegisterTokenRepository::class)
 */
class RegisterToken
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $token;

    /**
     * @ORM\Column(type="date")
     */
    private $creationDate;

    /**
     * @ORM\Column(type="date")
     */
    private $validityDate;

    /**
     * @ORM\OneToOne(targetEntity=User::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    const TOKEN_VALIDITY_MINUTES = 10;
    const TOKEN_LENGTH = 32;

    public function __construct(){
        $this->creationDate = new \DateTime();
        $this->validityDate = clone $this->creationDate;
        $this->validityDate->modify('+'.self::TOKEN_VALIDITY_MINUTES." minutes");
        $this->generateToken();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function generateToken(): self
    {
        return $this->setToken(openssl_random_pseudo_bytes(self::TOKEN_LENGTH));
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getValidityDate(): ?\DateTimeInterface
    {
        return $this->validityDate;
    }

    public function setValidityDate(\DateTimeInterface $validityDate): self
    {
        $this->validityDate = $validityDate;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
