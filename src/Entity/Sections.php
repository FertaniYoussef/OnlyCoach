<?php

namespace App\Entity;

use App\Repository\SectionsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SectionsRepository::class)]
class Sections
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $Index_section = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Titre = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbresources = null;

    #[ORM\ManyToOne(inversedBy: 'id_sections', cascade: ["PERSIST"])]
    private ?Cours $cours = null;

    #[ORM\OneToMany(mappedBy: 'sections', targetEntity: Ressources::class)]
    private Collection $id_ressources;

    public function __construct()
    {
        $this->id_ressources = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIndexSection(): ?int
    {
        return $this->Index_section;
    }

    public function setIndexSection(?int $Index_section): self
    {
        $this->Index_section = $Index_section;

        return $this;
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

    public function getNbresources(): ?int
    {
        return $this->nbresources;
    }

    public function setNbresources(?int $nbresources): self
    {
        $this->nbresources = $nbresources;

        return $this;
    }

    public function getCours(): ?Cours
    {
        return $this->cours;
    }

    public function setCours(?Cours $cours): self
    {
        $this->cours = $cours;

        return $this;
    }

    /**
     * @return Collection<int, Ressources>
     */
    public function getIdRessources(): Collection
    {
        return $this->id_ressources;
    }

    public function addIdRessource(Ressources $idRessource): self
    {
        if (!$this->id_ressources->contains($idRessource)) {
            $this->id_ressources->add($idRessource);
            $idRessource->setSections($this);
        }

        return $this;
    }

    public function removeIdRessource(Ressources $idRessource): self
    {
        if ($this->id_ressources->removeElement($idRessource)) {
            // set the owning side to null (unless already changed)
            if ($idRessource->getSections() === $this) {
                $idRessource->setSections(null);
            }
        }

        return $this;
    }
}
