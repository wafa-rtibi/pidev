<?php

namespace App\Entity;

use App\Repository\OffreRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OffreRepository::class)]
class Offre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id ;

    #[ORM\Column(length: 255)]
    private ?string $nom ;

    #[ORM\Column(length: 255)]
    private ?string $categorie;

    #[ORM\Column(length: 255)]
    private ?string $description ;

    #[ORM\Column(length: 255)]
    private ?string $etat ;

    #[ORM\Column(length: 255)]
    private ?DateTime $date_publication ;

    #[ORM\ManyToOne(inversedBy: 'offres')]
    private ?Utilisateur $offreur ;

    #[ORM\Column(length: 5)]
    private ?array $photos ;

    #[ORM\OneToMany(targetEntity: DemandeOffre::class, mappedBy: 'offre')]
    private Collection $demandes;

    public function __construct()
    {
        $this->demandes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): static
    {
        $this->etat = $etat;

        return $this;
    }

    public function getDatePublication(): ?DateTime
    {
        return $this->date_publication;
    }

    public function setDatePublication(DateTime $date_publication): static
    {
        $this->date_publication = $date_publication;

        return $this;
    }

    public function getOffreur(): ?Utilisateur
    {
        return $this->offreur;
    }

    public function setOffreur(?Utilisateur $offreur): static
    {
        $this->offreur = $offreur;

        return $this;
    }

    public function getPhotos(): ?array
    {
        return $this->photos;
    }

    public function setPhotos(array $photos): static
    {
        $this->photos = $photos;

        return $this;
    }

    /**
     * @return Collection<int, DemandeOffre>
     */
    public function getDemandes(): Collection
    {
        return $this->demandes;
    }

    public function addDemande(DemandeOffre $demande): static
    {
        if (!$this->demandes->contains($demande)) {
            $this->demandes->add($demande);
            $demande->setOffre($this);
        }

        return $this;
    }

    public function removeDemande(DemandeOffre $demande): static
    {
        if ($this->demandes->removeElement($demande)) {
            // set the owning side to null (unless already changed)
            if ($demande->getOffre() === $this) {
                $demande->setOffre(null);
            }
        }

        return $this;
    }
}
