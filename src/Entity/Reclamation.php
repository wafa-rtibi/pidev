<?php

namespace App\Entity;

use App\Repository\ReclamationRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReclamationRepository::class)]
class Reclamation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id ;

    #[ORM\Column(length: 255)]
    #} #[Assert\DateTime(message: 'This value is not a valid datetime')]{#}
    private ?DateTime $date_reclamation ;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"is required")]
    private ?string $objet_reclamation ;

    #[ORM\Column(length: 2000)]
    #[Assert\NotBlank(message:"is required")]
    #[Assert\Length(max: 2000, maxMessage: "La description ne doit pas dépasse 2000 caractères")]
    private ?string $description_reclamation ;



    #[ORM\ManyToOne(inversedBy: 'reclamations')]
    private ?Utilisateur $reclamateur ;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $statut_reclamation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateReclamation(): ?DateTime
    {
        return $this->date_reclamation;
    }

    public function setDateReclamation(DateTime $date_reclamation): static
    {
        $this->date_reclamation = $date_reclamation;

        return $this;
    }

    public function getObjetReclamation(): ?string
    {
        return $this->objet_reclamation;
    }

    public function setObjetReclamation(?string $objet_reclamation): self
    {
        $this->objet_reclamation = $objet_reclamation;

        return $this;
    }

    public function getDescriptionReclamation(): ?string
    {
        return $this->description_reclamation;
    }

    public function setDescriptionReclamation(string $description_reclamation): static
    {
        $this->description_reclamation = $description_reclamation;

        return $this;
    }

    public function getReclamateur(): ?Utilisateur
    {
        return $this->reclamateur;
    }

    public function setReclamateur(?Utilisateur $reclamateur): static
    {
        $this->reclamateur = $reclamateur;

        return $this;
    }

    public function getStatutReclamation(): ?string
    {
        return $this->statut_reclamation;
    }

    public function setStatutReclamation(?string $statut_reclamation): static
    {
        $this->statut_reclamation = $statut_reclamation;

        return $this;
    }
}
