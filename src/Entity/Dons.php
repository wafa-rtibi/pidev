<?php

namespace App\Entity;

use App\Repository\DonsRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DonsRepository::class)]
class Dons
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id ;

    #[ORM\Column()]
    private ? DateTime $date ;  

    #[ORM\ManyToOne(inversedBy: 'dons')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $donateur ;

    #[ORM\Column(length: 255)]
    private ?string $compagne_collect ;

    #[ORM\Column()]
    private ?float $montant ;

    #[ORM\ManyToOne(inversedBy: 'orgdons')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Organisation $organisation ;



    public function getId(): ?int
    {
        return $this->id;
    }

  

    public function getDate(): ?DateTime
    {
        return $this->date;
    }

    public function setDate(DateTime $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getDonateur(): ?Utilisateur
    {
        return $this->donateur;
    }

    public function setDonateur(?Utilisateur $donateur): static
    {
        $this->donateur = $donateur;

        return $this;
    }

    public function getCompagneCollect(): ?string
    {
        return $this->compagne_collect;
    }

    public function setCompagneCollect(string $compagne_collect): static
    {
        $this->compagne_collect = $compagne_collect;

        return $this;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): static
    {
        $this->montant = $montant;

        return $this;
    }

    public function getOrganisation(): ?Organisation
    {
        return $this->organisation;
    }

    public function setOrganisation(?Organisation $organisation): static
    {
        $this->organisation = $organisation;

        return $this;
    }

   
    
}
