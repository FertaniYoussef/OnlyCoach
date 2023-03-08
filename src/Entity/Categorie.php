<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Categorie
 *
 * @ORM\Table(name="categorie")
 * @ORM\Entity
 */
class Categorie
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=true)
     */
    private $type;

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
