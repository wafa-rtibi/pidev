<?php

namespace App\Entity;

use App\Repository\BlogRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: BlogRepository::class)]



class Blog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id ;


    #[ORM\Column(length: 255)]
    private ?string $contenu ;

    #[ORM\Column(length: 255)]
    private ?string $titre ;

    #[ORM\Column()]
    private ? DateTime $date_publication ;

   

    #[ORM\Column(length: 255)]
    private ?string $langue = null;

    #[ORM\ManyToOne(inversedBy: 'blogs')]
    private ?Utilisateur $auteur ;

    #[ORM\OneToMany(targetEntity: Commentaire::class, mappedBy: 'blog')]
    private Collection $comantaires;

    #[ORM\ManyToMany(targetEntity: Utilisateur::class, inversedBy: 'intearactionBlog')]
    private Collection $interactions;

    #[ORM\Column()]
    private ? bool $statut = false ;

   

    public function __construct()
    {
        $this->comantaires = new ArrayCollection();
        $this->interactions = new ArrayCollection();
       
    }

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

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

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

  

    public function getLangue(): ?string
    {
        return $this->langue;
    }

    public function setLangue(string $langue): static
    {
        $this->langue = $langue;

        return $this;
    }

    public function getAuteur(): ?Utilisateur
    {
        return $this->auteur;
    }

    public function setAuteur(?Utilisateur $auteur): static
    {
        $this->auteur = $auteur;

        return $this;
    }

    /**
     * @return Collection<int, Commentaire>
     */
    public function getComantaires(): Collection
    {
        return $this->comantaires;
    }

    public function addComantaire(Commentaire $comantaire): static
    {
        if (!$this->comantaires->contains($comantaire)) {
            $this->comantaires->add($comantaire);
            $comantaire->setBlog($this);
        }

        return $this;
    }

    public function removeComantaire(Commentaire $comantaire): static
    {
        if ($this->comantaires->removeElement($comantaire)) {
            // set the owning side to null (unless already changed)
            if ($comantaire->getBlog() === $this) {
                $comantaire->setBlog(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Utilisateur>
     */
    public function getInteractions(): Collection
    {
        return $this->interactions;
    }

    public function addInteraction(Utilisateur $interaction): static
    {
        if (!$this->interactions->contains($interaction)) {
            $this->interactions->add($interaction);
        }

        return $this;
    }

    public function removeInteraction(Utilisateur $interaction): static
    {
        $this->interactions->removeElement($interaction);

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

  

   

   
}
