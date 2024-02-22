<?php

namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\UtilisateurRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
#[ORM\Table(name: "utilisateur")]
#[Vich\Uploadable]

class Utilisateur implements UserInterface
{
    #[ORM\Column(length: 255)]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    private ?int $id ;



    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"The name cannot be empty.")]
    #[Assert\Length(
        min:2,
        max:25,
        minMessage:"The name must be at least {{ limit }} characters.",
        maxMessage:"The name cannot be longer than {{ limit }} characters."
    )]    
    private ?string $nom ;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"The first name cannot be empty.")]
    #[Assert\Length(
        min:2,
        max:25,
        minMessage:"The first name must be at least {{ limit }} characters.",
        maxMessage:"The first name cannot be longer than {{ limit }} characters."
    )]
    private ?string $prenom ;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "The email cannot be empty.")]
    #[Assert\Email(message: "The email address is not valid.")]
    private ?string $email;

    #[ORM\Column(length: 255)]
    #[Assert\Length(
        min:3,
        max:255,
        minMessage:"Le nom d'utilisateur doit avoir au moins {{ limit }} caractères.",
        maxMessage:"Le nom d'utilisateur ne peut pas dépasser {{ limit }} caractères."
    )]
    #[Assert\Regex(
        pattern:"/^[a-zA-Z0-9_]+$/",
        message:"Le nom d'utilisateur ne peut contenir que des lettres, des chiffres et des caractères soulignés."
    )]
    private ?string $Username;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "The password cannot be empty.")]
    #[Assert\Length(
        min: 6,
        minMessage: "The password must be at least {{ limit }} characters.",
        max: 255,
        maxMessage: "The password cannot be longer than {{ limit }} characters."
    )]
    #[Assert\Regex(
        pattern: "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).{6,}$/",
        message: "The password must contain at least one uppercase letter, one lowercase letter, and one digit."
    )]
    private ?string $mdp;


 #[ORM\Column(length: 255)]
    private ?DateTime $dateinscription;

    // #[ORM\Column(length: 255, nullable:true)]
    // private ?string $photoprofil="";

   
    // #[Vich\UploadableField(mapping: 'photoprofil', fileNameProperty: 'photoprofil')]
    // #[ORM\Column(length:255, nullable: true)]
    // private ?File $photoprofil_file=null;

    // #[ORM\Column(length:255, nullable: true)]
    // private ?DateTime $updatedAt = null;
    
    #[ORM\Column(length: 255)]
    // #[Assert\Length(
    //     min: 23,
    //     max: 23,
    //     exactMessage: "The RIB must be exactly {{ limit }} characters."
    // )]
    // #[Assert\Regex(
    //     pattern: "/^[0-9]+$/",
    //     message: "The RIB must contain only numeric characters."
    // )]
    private ?string $rib;

    #[ORM\Column(length: 255)]
    private ?string $adresse;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "The telephone cannot be empty.")]
    #[Assert\Type(type: "numeric", message: "The telephone must be a numeric value.")]
    #[Assert\Length(
        min: 8,
        max: 8,
        minMessage: "The telephone must be at least {{ limit }} digits.",
        maxMessage: "The telephone cannot be longer than {{ limit }} digits."
    )]
    private ?int $tel;

    // #[ORM\Column(length: 255)]
    // #[Assert\Range(min: 1, max: 5)]
    // private ?int $note = 0;

    // #[ORM\Column(length: 255)]
    // private ?string $statut = "débutant";/////// nahiw staut   snnn 



    #[ORM\Column(length: 255)]
    private ?array $roles = [];

    #[ORM\Column(length: 255)]
    private ?bool $isActive = true;

    
 
    #[ORM\Column(length: 255)]
    private ?string $salt = '0741485';





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


    #[ORM\ManyToMany(targetEntity: Evenement::class, mappedBy: 'participants')]
    private Collection $evenements;

 
    public function __construct()
    {
        $this->id = null; // Initialize ID

        $this->dons = new ArrayCollection();
        $this->offres = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
        $this->reclamations = new ArrayCollection();
        $this->blogs = new ArrayCollection();
        $this->intearactionBlog = new ArrayCollection();
        $this->evenements = new ArrayCollection();
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


    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }


    public function getMdp(): ?string
    {
        return $this->mdp;
    }

    public function setMdp(string $mdp): self
    {
        $this->mdp = $mdp;

        return $this;
    }

    public function getdateinscription(): ?DateTime
    {
        return $this->dateinscription;
    }

    public function setdateinscription(DateTime $dateinscription): self
    {
        $this->dateinscription = $dateinscription;

        return $this;
    }

    // public function getphotoprofil(): ?string
    // {                                               
    //     return  $this->photoprofil;

    //   }

    // public function setphotoProfil(string $photoprofil): self
    // {
    //     $this->photoprofil = $photoprofil;
    //     return $this;
    // }
    // public function setPhotoprofilFile(File $photoprofil = null)
    // {
    //     $this->photoprofil_file = $photoprofil;
    //     if ($photoprofil) {
    //         $this->updatedAt = new DateTime('now');
    //     }
    // }
    
    // public function getPhotoprofilFile(): string
    // {
    //     return $this->photoprofil_file;
    // }
    
    // public function setUpdatedAt(?DateTime $updatedAt)
    // {
    //     $this->updatedAt = $updatedAt;
    // }
    
    // public function getUpdatedAt(): ?DateTime
    // {
    //     return $this->updatedAt;
    // }


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

    public function addBlog(Blog $blog): self
    {
        if (!$this->blogs->contains($blog)) {
            $this->blogs->add($blog);
            $blog->setAuteur($this);
        }

        return $this;
    }

    public function removeBlog(Blog $blog): self
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

    public function addIntearactionBlog(Blog $intearactionBlog): self
    {
        if (!$this->intearactionBlog->contains($intearactionBlog)) {
            $this->intearactionBlog->add($intearactionBlog);
            $intearactionBlog->addInteraction($this);
        }

        return $this;
    }

    public function removeIntearactionBlog(Blog $intearactionBlog): self
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

    public function setRib(int $rib): self
    {
        $this->rib = $rib;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getTel(): ?int
    {
        return $this->tel;
    }

    public function setTel(int $tel): self
    {
        $this->tel = $tel;

        return $this;
    }

    // public function getNote(): ?int
    // {
    //     return $this->note;
    // }

    // public function setNote(int $note): self  //nahina la classe enumeration f status w bsh naawdhouha b methoode if rating=<2 status'debutant' w l method hedhy thji ttaht set merci 
    //  {
    //     $this->note = $note;

    //     return $this;
    // }

    // public function getStatut(): ?string
    // {
    //     return $this->statut;
    // }

    // public function setStatut(string $statut): self
    // {
    //     $this->statut = $statut;

    //     return $this;
    // }

    public function getroles(): ?array
    {   
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);

    }

    public function setroles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getSalt(): ?string
    {
        return $this->salt;
    }

    public function setSalt(string $salt): self
    {
        $this->salt = $salt;

        return $this;
    }



    public function eraseCredentials(): void
    {
        $this->mdp = null;

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

    public function getUsername(): ?string
    {
        return $this->Username;
    }

    public function setUsername(string $Username): static
    {
        $this->Username = $Username;

        return $this;
    }


    public function getPassword(): ?string
    {
        return $this->mdp;
    }

}
