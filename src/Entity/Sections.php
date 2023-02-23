<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sections
 *
 * @ORM\Table(name="sections", indexes={@ORM\Index(name="IDX_2B9643987ECF78B0", columns={"cours_id"})})
 * @ORM\Entity
 */
class Sections
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
     * @var int|null
     *
     * @ORM\Column(name="index_section", type="integer", nullable=true)
     */
    private $indexSection;

    /**
     * @var string|null
     *
     * @ORM\Column(name="titre", type="string", length=255, nullable=true)
     */
    private $titre;

    /**
     * @var int|null
     *
     * @ORM\Column(name="nbresources", type="integer", nullable=true)
     */
    private $nbresources;

    /**
     * @var \Cours
     *
     * @ORM\ManyToOne(targetEntity="Cours")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cours_id", referencedColumnName="id")
     * })
     */
    private $cours;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIndexSection(): ?int
    {
        return $this->indexSection;
    }

    public function setIndexSection(?int $indexSection): self
    {
        $this->indexSection = $indexSection;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(?string $titre): self
    {
        $this->titre = $titre;

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


}
