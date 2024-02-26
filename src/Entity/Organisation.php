<?php

namespace App\Entity;

use App\Repository\OrganisationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: OrganisationRepository::class)]
class Organisation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id ;

    #[ORM\Column(length: 255)]
    private ?string $nom_organisation ;

    #[ORM\Column(length: 255)]
    private ?string $description ;

    #[ORM\Column(length: 24)]

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le RIB ne peut pas être vide.")
     * @Assert\Length(
     *      min = 12,
     *      minMessage = "Le RIB doit faire au moins {{ limit }} caractères."
     * )
     */
    private ?string $rib ;

    #[ORM\Column(length: 255)]
    private ?string $adresse ;

    #[ORM\OneToMany(targetEntity: Dons::class, mappedBy: 'organisation')]
    private Collection $orgdons;

    public function __construct()
    {
        $this->orgdons = new ArrayCollection();
    }

 


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomOrganisation(): ?string
    {
        return $this->nom_organisation;
    }

    public function setNomOrganisation(string $nom_organisation): static
    {
        $this->nom_organisation = $nom_organisation;

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

    public function getRib(): ?string
    {
        return $this->rib;
    }

    public function setRib(string $rib): self
    {
        $this->rib = $rib;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * @return Collection<int, Dons>
     */
    public function getOrgdons(): Collection
    {
        return $this->orgdons;
    }

    public function addOrgdon(Dons $orgdon): static
    {
        if (!$this->orgdons->contains($orgdon)) {
            $this->orgdons->add($orgdon);
            $orgdon->setOrganisation($this);
        }

        return $this;
    }

    public function removeOrgdon(Dons $orgdon): static
    {
        if ($this->orgdons->removeElement($orgdon)) {
            // set the owning side to null (unless already changed)
            if ($orgdon->getOrganisation() === $this) {
                $orgdon->setOrganisation(null);
            }
        }

        return $this;
    }

 
    public function __toString(): string
    {
        return $this->nom_organisation ?? '';
    }
     
}
