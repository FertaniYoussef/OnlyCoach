<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Metadata\ApiResource;

#[ApiResource]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\Email]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\NotBlank]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank]
    private ?string $Nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank]
    private ?string $Prenom = null;

    #[ORM\OneToOne(mappedBy: 'id_user', cascade: ['persist', 'remove'])]
    private ?Coach $coach = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Abonnement::class)]
    private Collection $id_abonnement;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Feedback::class)]
    private Collection $id_feedback;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Adherents::class)]
    private Collection $id_adherent;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Rating::class)]
    private Collection $id_rating;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $picture = null;

    public function __construct()
    {
        $this->id_abonnement = new ArrayCollection();
        $this->id_feedback = new ArrayCollection();
        $this->cours = new ArrayCollection();
        $this->id_adherent = new ArrayCollection();
        $this->id_rating = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getCoach(): ?Coach
    {
        return $this->coach;
    }

    public function setCoach(?Coach $coach): self
    {
        // unset the owning side of the relation if necessary
        if ($coach === null && $this->coach !== null) {
            $this->coach->setIdUser(null);
        }

        // set the owning side of the relation if necessary
        if ($coach !== null && $coach->getIdUser() !== $this) {
            $coach->setIdUser($this);
        }

        $this->coach = $coach;

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
            $idAbonnement->setUser($this);
        }

        return $this;
    }

    public function removeIdAbonnement(Abonnement $idAbonnement): self
    {
        if ($this->id_abonnement->removeElement($idAbonnement)) {
            // set the owning side to null (unless already changed)
            if ($idAbonnement->getUser() === $this) {
                $idAbonnement->setUser(null);
            }
        }

        return $this;
    }
    #handle Subscription existence
    public function isSubscribedTo(Coach $coach): bool
    {
        return $this->id_abonnement->exists(function ($key, $id_abonnement) use ($coach) {
            return $id_abonnement->getCoach() === $coach;
        });
    }

    /**
     * @return Collection<int, Feedback>
     */
    public function getIdFeedback(): Collection
    {
        return $this->id_feedback;
    }

    public function addIdFeedback(Feedback $idFeedback): self
    {
        if (!$this->id_feedback->contains($idFeedback)) {
            $this->id_feedback->add($idFeedback);
            $idFeedback->setUser($this);
        }

        return $this;
    }

    public function removeIdFeedback(Feedback $idFeedback): self
    {
        if ($this->id_feedback->removeElement($idFeedback)) {
            // set the owning side to null (unless already changed)
            if ($idFeedback->getUser() === $this) {
                $idFeedback->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Adherents>
     */
    public function getIdAdherent(): Collection
    {
        return $this->id_adherent;
    }

    public function addIdAdherent(Adherents $idAdherent): self
    {
        if (!$this->id_adherent->contains($idAdherent)) {
            $this->id_adherent->add($idAdherent);
            $idAdherent->setUser($this);
        }

        return $this;
    }

    public function removeIdAdherent(Adherents $idAdherent): self
    {
        if ($this->id_adherent->removeElement($idAdherent)) {
            // set the owning side to null (unless already changed)
            if ($idAdherent->getUser() === $this) {
                $idAdherent->setUser(null);
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
            $idRating->setUser($this);
        }

        return $this;
    }

    public function removeIdRating(Rating $idRating): self
    {
        if ($this->id_rating->removeElement($idRating)) {
            // set the owning side to null (unless already changed)
            if ($idRating->getUser() === $this) {
                $idRating->setUser(null);
            }
        }

        return $this;
    }

    public function __toString() {
        return $this->id;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }
}
