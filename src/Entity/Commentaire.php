<?php

namespace App\Entity;

use App\Repository\CommentaireRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentaireRepository::class)]
class Commentaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id ;

    #[ORM\Column(length: 255)]
    private ?string $contenu ;

    #[ORM\Column(length: 255)]
    private ?bool $statut = false;

    #[ORM\ManyToOne(inversedBy: 'commentaires')]
    private ?Utilisateur $commenteur ;

    #[ORM\ManyToOne(inversedBy: 'comantaires')]
    private ?Blog $blog ;

    public function getId(): ?int
    {
        return $this->id;
    }
   
    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): static
    {
        $this->contenu = $contenu;

        return $this;
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

    public function getCommenteur(): ?Utilisateur
    {
        return $this->commenteur;
    }

    public function setCommenteur(?Utilisateur $commenteur): static
    {
        $this->commenteur = $commenteur;

        return $this;
    }

    public function getBlog(): ?Blog
    {
        return $this->blog;
    }

    public function setBlog(?Blog $blog): static
    {
        $this->blog = $blog;

        return $this;
    }
}
