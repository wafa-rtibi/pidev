<?php

namespace App\Entity;

use App\Repository\ReponseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;








#[ORM\Entity(repositoryClass: ReponseRepository::class)]
class Reponse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\Column(length: 2000)]
    //pour le contrôle de saisie 
    #[Assert\NotBlank(message:"Description is required")]
    #[Assert\Length(max:2000, maxMessage:"Description must be at most {{ limit }} characters long")]
    private ?string $description;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message:"Date of response is required")]
    private ?\DateTimeInterface $date_reponse = null;

    #[ORM\OneToOne(inversedBy: 'reponse', cascade: ['persist', 'remove'])]
    private ?Reclamation $reclam_reponse = null;

    #[ORM\ManyToOne(inversedBy: 'reponses')]
    private ?Administrateur $admin = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDateReponse(): ?\DateTimeInterface
    {
        return $this->date_reponse;
    }

    public function setDateReponse(\DateTimeInterface $date_reponse): static
    {
        $this->date_reponse = $date_reponse;

        return $this;
    }

    public function getReclamReponse(): ?Reclamation
    {
        return $this->reclam_reponse;
    }

    public function setReclamReponse(?Reclamation $reclam_reponse): static
    {
        $this->reclam_reponse = $reclam_reponse;

        return $this;
    }

    public function getAdmin(): ?Administrateur
    {
        return $this->admin;
    }

    public function setAdmin(?Administrateur $admin): static
    {
        $this->admin = $admin;

        return $this;
    }

    
   

  

   
}
