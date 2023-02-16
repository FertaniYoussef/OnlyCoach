<?php

namespace App\Entity;

use App\Repository\CoachRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CoachRepository::class)]
class Coach
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Prenom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Picture = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Description = null;

    #[ORM\Column(nullable: true)]
    private ?float $Prix = null;

    #[ORM\Column(nullable: true)]
    private ?float $Rating = null;

    #[ORM\OneToOne(inversedBy: 'coach', cascade: ['persist', 'remove'])]
    private ?User $id_user = null;

    #[ORM\OneToOne(mappedBy: 'id_coach', cascade: ['persist', 'remove'])]
    private ?Offre $offre = null;

    #[ORM\ManyToOne(inversedBy: 'id_coach')]
    private ?Categorie $categorie = null;

    #[ORM\OneToMany(mappedBy: 'coach', targetEntity: Abonnement::class)]
    private Collection $id_abonnement;

    public function __construct()
    {
        $this->id_abonnement = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(?string $Nom): self
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->Prenom;
    }

    public function setPrenom(?string $Prenom): self
    {
        $this->Prenom = $Prenom;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->Picture;
    }

    public function setPicture(?string $Picture): self
    {
        $this->Picture = $Picture;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(?string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->Prix;
    }

    public function setPrix(?float $Prix): self
    {
        $this->Prix = $Prix;

        return $this;
    }

    public function getRating(): ?float
    {
        return $this->Rating;
    }

    public function setRating(?float $Rating): self
    {
        $this->Rating = $Rating;

        return $this;
    }

    public function getIdUser(): ?User
    {
        return $this->id_user;
    }

    public function setIdUser(?User $id_user): self
    {
        $this->id_user = $id_user;

        return $this;
    }

    public function getOffre(): ?Offre
    {
        return $this->offre;
    }

    public function setOffre(?Offre $offre): self
    {
        // unset the owning side of the relation if necessary
        if ($offre === null && $this->offre !== null) {
            $this->offre->setIdCoach(null);
        }

        // set the owning side of the relation if necessary
        if ($offre !== null && $offre->getIdCoach() !== $this) {
            $offre->setIdCoach($this);
        }

        $this->offre = $offre;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * @return Collection<int, Abonnement>
     */
    public function getIdAbonnement(): Collection
    {
        return $this->id_abonnement;
    }

    public function addIdAbonnement(Abonnement $idAbonnement): self
    {
        if (!$this->id_abonnement->contains($idAbonnement)) {
            $this->id_abonnement->add($idAbonnement);
            $idAbonnement->setCoach($this);
        }

        return $this;
    }

    public function removeIdAbonnement(Abonnement $idAbonnement): self
    {
        if ($this->id_abonnement->removeElement($idAbonnement)) {
            // set the owning side to null (unless already changed)
            if ($idAbonnement->getCoach() === $this) {
                $idAbonnement->setCoach(null);
            }
        }

        return $this;
    }


    public function __toString() {
        return $this->id;
    }
}