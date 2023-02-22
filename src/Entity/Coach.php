<?php

namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;
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
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message:'le nom ne peut pas etre vide ')]
    private ?string $Nom = null;

    

    #[ORM\Column(length: 255, nullable: false)]
    #[Assert\NotBlank(message:'le prenom ne peut pas etre vide ')]
    
    private ?string $Prenom = null;

    /*
    @Assert\NotBlank
    */

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Picture = null;

    /*
    @Assert\NotBlank
    */

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Description = null;
    
    /*
    @Assert\NotBlank
    */

    #[ORM\Column(nullable: true)]
    private ?float $Prix = null;

    #[ORM\Column(nullable: true)]
    private ?float $Rating = null;


    #[ORM\OneToOne(inversedBy: 'coach', cascade: ['persist', 'remove'])]
    #[Assert\NotBlank(message:'Ce champ est obligatoire ')]
    
    private ?User $id_user = null;

    #[ORM\OneToOne(mappedBy: 'id_coach', cascade: ['persist', 'remove'])]
    private ?Offre $offre = null;

    #[ORM\ManyToOne(inversedBy: 'id_coach', cascade: ['persist', 'remove']) ]
    #[Assert\NotBlank(message:'Ce champ est obligatoire ')]
    

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
}
