<?php

namespace App\Entity;

use App\Repository\ReponseRepository;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

#[ORM\Entity(repositoryClass: ReponseRepository::class)]
class Reponse implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $texte = null;

    #[ORM\OneToOne(inversedBy: 'reponse', cascade: ['persist', 'remove'])]
    private ?Feedback $id_feedback = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTexte(): ?string
    {
        return $this->texte;
    }

    public function setTexte(string $texte): self
    {
        $this->texte = $texte;

        return $this;
    }

    public function getIdFeedback(): ?Feedback
    {
        return $this->id_feedback;
    }

    public function setIdFeedback(?Feedback $id_feedback): self
    {
        $this->id_feedback = $id_feedback;

        return $this;
    }

    public function jsonSerialize(): array
    {
        return array(
            'id' => $this->id,
            'feedback' => $this->id_feedback,
            'texte' => $this->texte

        );
    }

    public function constructor($feedback, $texte)
    {
        $this->id_feedback = $feedback;
        $this->texte = $texte;
    }
}
