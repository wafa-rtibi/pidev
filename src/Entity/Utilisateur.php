<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
class Utilisateur
{
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id ;


    #[ORM\Column(length: 255)]
    private ?string $nom ;

    #[ORM\Column(length: 255)]
    private ?string $prenom ;  
    // #[ORM\Column(length: 255)]
    // private ?string $Username;

    #[ORM\Column(length: 255)]
    private ?string $email ;

    #[ORM\Column(length: 255)]
    private ?string $mdp ;

    #[ORM\Column(length: 255)]
    private ?DateTime $date_inscription ;

    #[ORM\Column(length: 255)]
    private ?string $photo_profil ;

    #[ORM\OneToMany(targetEntity: Dons::class, mappedBy: 'donateur')]
    private Collection $dons;

    #[ORM\OneToMany(targetEntity: Offre::class, mappedBy: 'offreur')]
    private Collection $offres;

    #[ORM\OneToMany(targetEntity: Commentaire::class, mappedBy: 'commenteur')]
    private Collection $commentaires;

    #[ORM\OneToMany(targetEntity: Reclamation::class, mappedBy: 'reclamateur')]
    private Collection $reclamations;

    #[ORM\ManyToOne(inversedBy: 'participants')]
    private ?Evenement $evenement ;

    #[ORM\OneToMany(targetEntity: Blog::class, mappedBy: 'auteur')]
    private Collection $blogs;

    #[ORM\ManyToMany(targetEntity: Blog::class, mappedBy: 'interactions')]
    private Collection $intearactionBlog;

    #[ORM\Column()]
    private ?int $rib ;

    #[ORM\Column(length: 255)]
    private ?string $adresse;

    #[ORM\Column(length: 255)]
    private ?int $tel ;

    #[ORM\Column(length: 255)]
    private ?int $note = 0;

    #[ORM\Column(length: 255)]
    private ?string $statut = "débutant";



    #[ORM\Column(length: 255)]
    private ?array $role = ["utilisateur"];

    #[ORM\Column(length: 255)]
    private ?bool $isActive = false;

  

    #[ORM\Column(length: 255)]
    private ?string $salt = null;

    #[ORM\ManyToMany(targetEntity: Evenement::class, mappedBy: 'participants')]
    private Collection $evenements;

    #[ORM\ManyToMany(targetEntity: Blog::class, mappedBy: 'favories')]
    private Collection $blogfavories;

    #[ORM\Column(length: 255)]
    private ?string $Username = null;
 
    public function __construct()
    {
        $this->dons = new ArrayCollection();
        $this->offres = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
        $this->reclamations = new ArrayCollection();
        $this->blogs = new ArrayCollection();
        $this->intearactionBlog = new ArrayCollection();
        $this->evenements = new ArrayCollection();
        $this->blogfavories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getMdp(): ?string
    {
        return $this->mdp;
    }

    public function setMdp(string $mdp): static
    {
        $this->mdp = $mdp;

        return $this;
    }

    public function getDateInscription(): ?DateTime
    {
        return $this->date_inscription;
    }

    public function setDateInscription(DateTime $date_inscription): static
    {
        $this->date_inscription = $date_inscription;

        return $this;
    }

    public function getPhotoProfil(): ?string
    {
        return $this->photo_profil;
    }

    public function setPhotoProfil(string $photo_profil): static
    {
        $this->photo_profil = $photo_profil;

        return $this;
    }

    /**
     * @return Collection<int, Dons>
     */
    public function getDons(): Collection
    {
        return $this->dons;
    }

    public function addDon(Dons $don): static
    {
        if (!$this->dons->contains($don)) {
            $this->dons->add($don);
            $don->setDonateur($this);
        }

        return $this;
    }

    public function removeDon(Dons $don): static
    {
        if ($this->dons->removeElement($don)) {
            // set the owning side to null (unless already changed)
            if ($don->getDonateur() === $this) {
                $don->setDonateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Offre>
     */
    public function getOffres(): Collection
    {
        return $this->offres;
    }

    public function addOffre(Offre $offre): static
    {
        if (!$this->offres->contains($offre)) {
            $this->offres->add($offre);
            $offre->setOffreur($this);
        }

        return $this;
    }

    public function removeOffre(Offre $offre): static
    {
        if ($this->offres->removeElement($offre)) {
            // set the owning side to null (unless already changed)
            if ($offre->getOffreur() === $this) {
                $offre->setOffreur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Commentaire>
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): static
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires->add($commentaire);
            $commentaire->setCommenteur($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): static
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getCommenteur() === $this) {
                $commentaire->setCommenteur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Reclamation>
     */
    public function getReclamations(): Collection
    {
        return $this->reclamations;
    }

    public function addReclamation(Reclamation $reclamation): static
    {
        if (!$this->reclamations->contains($reclamation)) {
            $this->reclamations->add($reclamation);
            $reclamation->setReclamateur($this);
        }

        return $this;
    }

    public function removeReclamation(Reclamation $reclamation): static
    {
        if ($this->reclamations->removeElement($reclamation)) {
            // set the owning side to null (unless already changed)
            if ($reclamation->getReclamateur() === $this) {
                $reclamation->setReclamateur(null);
            }
        }

        return $this;
    }

   
    /**
     * @return Collection<int, Blog>
     */
    public function getBlogs(): Collection
    {
        return $this->blogs;
    }

    public function addBlog(Blog $blog): static
    {
        if (!$this->blogs->contains($blog)) {
            $this->blogs->add($blog);
            $blog->setAuteur($this);
        }

        return $this;
    }

    public function removeBlog(Blog $blog): static
    {
        if ($this->blogs->removeElement($blog)) {
            // set the owning side to null (unless already changed)
            if ($blog->getAuteur() === $this) {
                $blog->setAuteur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Blog>
     */
    public function getIntearactionBlog(): Collection
    {
        return $this->intearactionBlog;
    }

    public function addIntearactionBlog(Blog $intearactionBlog): static
    {
        if (!$this->intearactionBlog->contains($intearactionBlog)) {
            $this->intearactionBlog->add($intearactionBlog);
            $intearactionBlog->addInteraction($this);
        }

        return $this;
    }

    public function removeIntearactionBlog(Blog $intearactionBlog): static
    {
        if ($this->intearactionBlog->removeElement($intearactionBlog)) {
            $intearactionBlog->removeInteraction($this);
        }

        return $this;
    }

    public function getRib(): ?int
    {
        return $this->rib;
    }

    public function setRib(int $rib): static
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

    public function getTel(): ?int
    {
        return $this->tel;
    }

    public function setTel(int $tel): static
    {
        $this->tel = $tel;

        return $this;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(int $note): static  //nahina la classe enumeration f status w bsh naawdhouha b methoode if rating=<2 status'debutant' w l method hedhy thji ttaht set merci 
     {
        $this->note = $note;

        return $this;
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

    public function getRole(): ?array
    {
        return $this->role;
    }

    public function setRole(array $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getSalt(): ?string
    {
        return $this->salt;
    }

    public function setSalt(string $salt): static
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * @return Collection<int, Evenement>
     */
    public function getEvenements(): Collection
    {
        return $this->evenements;
    }

    public function addEvenement(Evenement $evenement): static
    {
        if (!$this->evenements->contains($evenement)) {
            $this->evenements->add($evenement);
            $evenement->addParticipant($this);
        }

        return $this;
    }

    public function removeEvenement(Evenement $evenement): static
    {
        if ($this->evenements->removeElement($evenement)) {
            $evenement->removeParticipant($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Blog>
     */
    public function getBlogfavories(): Collection
    {
        return $this->blogfavories;
    }

    public function addBlogfavory(Blog $blogfavory): static
    {
        if (!$this->blogfavories->contains($blogfavory)) {
            $this->blogfavories->add($blogfavory);
            $blogfavory->addFavory($this);
        }

        return $this;
    }

    public function removeBlogfavory(Blog $blogfavory): static
    {
        if ($this->blogfavories->removeElement($blogfavory)) {
            $blogfavory->removeFavory($this);
        }

        return $this;
    }
    // public function getUsername(): ?string
    // {
    //     return $this->Username;
    // }

    // public function setUsername(?string $Username): static
    // {
    //     // Si la valeur du username est nulle, générez-le en combinant nom et prénom
    //     if ($Username === null) {
    //         $Username = strtolower($this->getNom() . ' ' . $this->getPrenom());
    //     }

    //     $this->Username = $Username;

    //     return $this;
    // }

    public function getUsername(): ?string
    {
        return $this->Username;
    }

    public function setUsername(string $Username): static
    {
        $this->Username = $Username;

        return $this;
    }
   
}
