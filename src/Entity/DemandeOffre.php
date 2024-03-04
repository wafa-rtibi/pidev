<?php

namespace App\Entity;

use App\Repository\DemandeOffreRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Entity(repositoryClass: DemandeOffreRepository::class)]
class DemandeOffre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id ;

    #[ORM\Column()]
    private ?string $statut ="en attende";

    #[ORM\Column(length: 255)]
    private ?DateTime $date_creation ;

    #[ORM\ManyToOne(inversedBy: 'demandes')]
    private ?Offre $offre ;

    #[ORM\ManyToOne(inversedBy: 'demandeOffres')]
    private ?Utilisateur $demandeur = null;

    #[ORM\Column(length: 255,nullable:true)]
    #[Assert\Length(max:20, maxMessage: "Your message contains more than 10 characters.")]
    private ?string $message = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Offre $opposite ;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getDateCreation()
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

    public function getDemandeur(): ?Utilisateur
    {
        return $this->demandeur;
    }

    public function setDemandeur(?Utilisateur $demandeur): static
    {
        $this->demandeur = $demandeur;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function getOpposite(): ?Offre
    {
        return $this->opposite;
    }

    public function setOpposite(Offre $opposite): static
    {
        $this->opposite = $opposite;

        return $this;
    }
}
