<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieRepository::class)]
class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Type = null;

    #[ORM\OneToMany(mappedBy: 'categorie', targetEntity: Coach::class)]
    private Collection $id_coach;

    public function __construct()
    {
        $this->id_coach = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->Type;
    }

    public function setType(?string $Type): self
    {
        $this->Type = $Type;

        return $this;
    }

    /**
     * @return Collection<int, Coach>
     */
    public function getIdCoach(): Collection
    {
        return $this->id_coach;
    }

    public function addIdCoach(Coach $idCoach): self
    {
        if (!$this->id_coach->contains($idCoach)) {
            $this->id_coach->add($idCoach);
            $idCoach->setCategorie($this);
        }

        return $this;
    }

    public function removeIdCoach(Coach $idCoach): self
    {
        if ($this->id_coach->removeElement($idCoach)) {
            // set the owning side to null (unless already changed)
            if ($idCoach->getCategorie() === $this) {
                $idCoach->setCategorie(null);
            }
        }

        return $this;
    }

    public function __toString() {
        return $this->Type;
    }
}
