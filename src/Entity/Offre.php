<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Offre
 *
 * @ORM\Table(name="offre", uniqueConstraints={@ORM\UniqueConstraint(name="UNIQ_AF86866F6CCBBA04", columns={"id_coach_id"})})
 * @ORM\Entity
 */
class Offre
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
     * @Assert\NotBlank(message=" nom  est obligatoire")
     * @Assert\Type(type="string")
     * @var string|null
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @Assert\NotBlank(message=" prix  est obligatoire")
     * @Assert\Type(type="float")
     * @var float|null
     *
     * @ORM\Column(name="prix", type="float", precision=10, scale=0, nullable=true)
     */
    private $prix;

    /**
     * @Assert\NotBlank(message=" discount  est obligatoire")
     * @Assert\Type(type="float")
     * @var float|null
     *
     * @ORM\Column(name="discount", type="float", precision=10, scale=0, nullable=true)
     */
    private $discount;

       /**
     * @Assert\Type(type="float")
     * @var float|null
     *
     * @ORM\Column(name="prix_fin", type="float", precision=10, scale=0, nullable=true)
     */
    private $prixFin;
    






    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_deb", type="date", nullable=true)
     */


     
    private $dateDeb;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_fin", type="date", nullable=true)
     */
    private $dateFin;

    /**
     * @var \Coach
     *
     * @ORM\ManyToOne(targetEntity="Coach")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_coach_id", referencedColumnName="id")
     * })
     * 
     * 
     */
    private $idCoach;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(?float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }
    public function getPrixFin(): ?float
    {
        return $this->prixFin;
    }

    public function setPrixFin(?float $prix): self
    {
        $this->prixFin = $prix;

        return $this;
    }

    public function getDiscount(): ?float
    {
        return $this->discount;
    }

    public function setDiscount(?float $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

  



    public function getDateDeb(): ?\DateTimeInterface
    {
        return $this->dateDeb;
    }

    public function setDateDeb(?\DateTimeInterface $dateDeb): self
    {
        $this->dateDeb = $dateDeb;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(?\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getIdCoach(): ?Coach
    {
        return $this->idCoach;
    }
    public function id_coach(): ?Coach
    {
        return $this->idCoach;
    }
    public function setIdCoach(?Coach $idCoach): self
    {
        $this->idCoach = $idCoach;

        return $this;
    }


}
