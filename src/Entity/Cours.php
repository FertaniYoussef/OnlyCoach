<?php

namespace App\Entity;

use App\Repository\CoursRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CoursRepository::class)]
class Cours
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank]
    private ?string $Titre = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 10, max: 255)]
    private ?string $Description = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_creation = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbVues = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank]
    private ?string $cours_photo = null;

    #[ORM\OneToMany(mappedBy: 'cours', targetEntity: Adherents::class)]
    private Collection $id_adherents;

    #[ORM\OneToMany(mappedBy: 'cours', targetEntity: Rating::class)]
    private Collection $id_rating;

    #[ORM\OneToMany(mappedBy: 'cours', targetEntity: Sections::class, cascade: ['persist', 'remove'])]
    private Collection $id_sections;

    #[ORM\ManyToOne(inversedBy: 'cours')]
    private ?Coach $IdCoach = null;

    public function __construct()
    {
        $this->id_user_adherents = new ArrayCollection();
        $this->id_adherents = new ArrayCollection();
        $this->id_rating = new ArrayCollection();
        $this->id_sections = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->Titre;
    }

    public function setTitre(?string $Titre): self
    {
        $this->Titre = $Titre;

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

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->date_creation;
    }

    public function setDateCreation(?\DateTimeInterface $date_creation): self
    {
        $this->date_creation = $date_creation;

        return $this;
    }

    public function getNbVues(): ?int
    {
        return $this->nbVues;
    }

    public function setNbVues(?int $nbVues): self
    {
        $this->nbVues = $nbVues;

        return $this;
    }

    public function getCoursPhoto(): ?string
    {
        return $this->cours_photo;
    }

    public function setCoursPhoto(?string $cours_photo): self
    {
        $this->cours_photo = $cours_photo;

        return $this;
    }

    /**
     * @return Collection<int, Adherents>
     */
    public function getIdAdherents(): Collection
    {
        return $this->id_adherents;
    }

    public function addIdAdherent(Adherents $idAdherent): self
    {
        if (!$this->id_adherents->contains($idAdherent)) {
            $this->id_adherents->add($idAdherent);
            $idAdherent->setCours($this);
        }

        return $this;
    }

    public function removeIdAdherent(Adherents $idAdherent): self
    {
        if ($this->id_adherents->removeElement($idAdherent)) {
            // set the owning side to null (unless already changed)
            if ($idAdherent->getCours() === $this) {
                $idAdherent->setCours(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Rating>
     */
    public function getIdRating(): Collection
    {
        return $this->id_rating;
    }

    public function addIdRating(Rating $idRating): self
    {
        if (!$this->id_rating->contains($idRating)) {
            $this->id_rating->add($idRating);
            $idRating->setCours($this);
        }

        return $this;
    }

    public function removeIdRating(Rating $idRating): self
    {
        if ($this->id_rating->removeElement($idRating)) {
            // set the owning side to null (unless already changed)
            if ($idRating->getCours() === $this) {
                $idRating->setCours(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Sections>
     */
    public function getIdSections(): Collection
    {
        return $this->id_sections;
    }

    public function addIdSection(Sections $idSection): self
    {
        if (!$this->id_sections->contains($idSection)) {
            $this->id_sections->add($idSection);
            $idSection->setCours($this);
        }

        return $this;
    }

    public function removeIdSection(Sections $idSection): self
    {
        if ($this->id_sections->removeElement($idSection)) {
            // set the owning side to null (unless already changed)
            if ($idSection->getCours() === $this) {
                $idSection->setCours(null);
            }
        }

        return $this;
    }

    public function getIdCoach(): ?Coach
    {
        return $this->IdCoach;
    }

    public function setIdCoach(?Coach $IdCoach): self
    {
        $this->IdCoach = $IdCoach;

        return $this;
    }

}
