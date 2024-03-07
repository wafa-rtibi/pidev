<?php

namespace App\Entity;

use App\Repository\DemandeOffreRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: DemandeOffreRepository::class)]



class DemandeOffre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id ;

    #[ORM\Column(length: 255)]
    private ?bool $statut = false;

    #[ORM\Column(length: 255)]
    private ?DateTime $date_creation ;

    #[ORM\ManyToOne(inversedBy: 'demandes')]
    private ?Offre $offre ;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatut(): ?bool
    {
        return $this->statut;
    }

    public function setStatut(bool $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getDateCreation(): ?DateTime
    {
        return $this->date_creation;
    }

    public function setDateCreation(DateTime $date_creation): static
    {
        $this->date_creation = $date_creation;

        return $this;
    }

    public function getOffre(): ?Offre
    {
        return $this->offre;
    }

    public function setOffre(?Offre $offre): static
    {
        $this->offre = $offre;

        return $this;
    }
}
