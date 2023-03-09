<?php

namespace App\Entity;

use App\Repository\FeedbackRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FeedbackRepository::class)]
class Feedback implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Veuillez Preciser le sujet !")]
    private ?string $Sujet ;

    #[ORM\Column(length: 255)]
   // #[Assert\Email(message:"The email '{{ value }}' is not a valid email ")]
    private ?string $email ;


    #[ORM\Column(length: 255)]
    #[Assert\Length(min: 10, max: 255,
     minMessage: 'Ta description  doit avoir {{ limit }} characters minimum',
     maxMessage: 'Ta description  doit avoir  {{ limit }} characters maximum',)]
    private ?string $description ;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_feedback = null;

    #[ORM\Column(nullable: true)]
    private ?int $status ;

    #[ORM\ManyToOne(inversedBy: 'id_feedback')]
    private ?User $user = null;

    #[ORM\OneToOne(mappedBy: 'id_feedback', cascade: ['persist', 'remove'])]
    private ?Reponse $reponse = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSujet(): ?string
    {
        return $this->Sujet;
    }

    public function setSujet(?string $Sujet): self
    {
        $this->Sujet = $Sujet;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDateFeedback(): ?\DateTimeInterface
    {
        return $this->date_feedback;
    }

    public function setDateFeedback(?\DateTimeInterface $date_feedback): self
    {
        $this->date_feedback = $date_feedback;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status): self
    {
        $this->status = $status;

        return $this;
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
}
