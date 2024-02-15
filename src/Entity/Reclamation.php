<?php

namespace App\Entity;

use App\Repository\ReclamationRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReclamationRepository::class)]
class Reclamation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id ;

    #[ORM\Column(length: 255)]
    private ?DateTime $date_reclamation ;

    #[ORM\Column(length: 255)]
    private ?string $objet_reclamation ;

    #[ORM\Column(length: 2000)]
    private ?string $description_reclamation ;

    #[ORM\Column(length: 255)]
    private ?string $statut_reclamation ;

    #[ORM\Column(length: 255)]
    private ?string $reponse_reclamation ;

    #[ORM\Column(length: 255)]
    private ?DateTime $date_reponse ;

    #[ORM\ManyToOne(inversedBy: 'reclamations')]
    private ?Utilisateur $reclamateur ;

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

    public function setObjetReclamation(string $objet_reclamation): static
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

    public function getStatutReclamation(): ?string
    {
        return $this->statut_reclamation;
    }

    public function setStatutReclamation(string $statut_reclamation): static
    {
        $this->statut_reclamation = $statut_reclamation;

        return $this;
    }

    public function getReponseReclamation(): ?string
    {
        return $this->reponse_reclamation;
    }

    public function setReponseReclamation(string $reponse_reclamation): static
    {
        $this->reponse_reclamation = $reponse_reclamation;

        return $this;
    }

    public function getDateReponse(): ?DateTime
    {
        return $this->date_reponse;
    }

    public function setDateReponse(DateTime $date_reponse): static
    {
        $this->date_reponse = $date_reponse;

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
}
