<?php

namespace App\Entity;

use App\Repository\ReclamationRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use App\Validator\Constraints\CustomNotBlank;







#[ORM\Entity(repositoryClass: ReclamationRepository::class)]
class Reclamation

{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id ;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Date of reclamation is required")]
    private ?DateTime $date_reclamation ;


   
    #[ORM\Column(length: 200)]
    
    #[Assert\NotBlank(message:"Description is required")]
    #[Assert\Length(max:200, maxMessage:"Description must be at most 200 characters long")]
    private ?string $description_reclamation ;




    #[ORM\ManyToOne(inversedBy: 'reclamations')]
    private ?Utilisateur $reclamateur ;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $statut_reclamation="Sent successfully";


   

 #[ORM\Column(length: 255)]
 #[Assert\NotBlank(message: "Type is required")]
 private ?string $type = null ;

    #[ORM\OneToOne(mappedBy: 'reclam_reponse', cascade: ['persist', 'remove'])]
    private ?Reponse $reponse = null;

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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getReponse(): ?Reponse
    {
        return $this->reponse;
    }

    public function setReponse(?Reponse $reponse): static
    {
        // unset the owning side of the relation if necessary
        if ($reponse === null && $this->reponse !== null) {
            $this->reponse->setReclamReponse(null);
        }

        // set the owning side of the relation if necessary
        if ($reponse !== null && $reponse->getReclamReponse() !== $this) {
            $reponse->setReclamReponse($this);
        }

        $this->reponse = $reponse;

        return $this;
    }

}
