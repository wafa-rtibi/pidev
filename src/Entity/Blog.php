<?php

namespace App\Entity;

use Symfony\Component\HttpFoundation\File\File;
use App\Repository\BlogRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @Vich\Uploadable
 */
#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: BlogRepository::class)]
class Blog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null ;


    #[ORM\Column(length: 255)]
    private ?string $contenu= null ;

    #[ORM\Column(length: 255)]
    private ?string $titre= null ;

    #[ORM\Column()]
    private ? DateTime $date_publication ;

   /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    
     #[ORM\Column()]
    private  $image ;


   /**
     * @Vich\UploadableField(mapping="blog_images", fileNameProperty="image")
     * 
     * @var File|null
     */
    #[Vich\UploadableField(mapping: "blog_images", fileNameProperty: "image")]
     private $imageFile = null;

    #[ORM\ManyToOne(inversedBy: 'blogs')]

    private ?Utilisateur $auteur= null ;

    #[ORM\OneToMany(targetEntity: Commentaire::class, mappedBy: 'blog')]
    private Collection $comantaires ;

    // #[ORM\ManyToMany(targetEntity: Utilisateur::class, inversedBy: 'intearactionBlog')]
    // private Collection $interactions;

    // #[ORM\ManyToMany(targetEntity: utilisateur::class, inversedBy: 'blogfavories')]
    // private Collection $favories;



    public function __construct()
    {
        $this->comantaires = new ArrayCollection();
        // $this->interactions = new ArrayCollection();
        // $this->favories = new ArrayCollection();
       
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
    public function setImageFile(File $imageFile)
    {
        $this->imageFile = $imageFile;
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function setImage($image): static
    {
        $this->image = $image;
        return $this;
    }

    public function getImage()
    {
        return $this->image;
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

    // /**
    //  * @return Collection<int, Utilisateur>
    //  */
    // public function getInteractions(): Collection
    // {
    //     return $this->interactions;
    // }

    // public function addInteraction(Utilisateur $interaction): static
    // {
    //     if (!$this->interactions->contains($interaction)) {
    //         $this->interactions->add($interaction);
    //     }

    //     return $this;
    // }

    // public function removeInteraction(Utilisateur $interaction): static
    // {
    //     $this->interactions->removeElement($interaction);

    //     return $this;
    // }

    // /**
    //  * @return Collection<int, utilisateur>
    //  */
    // public function getFavories(): Collection
    // {
    //     return $this->favories;
    // }

    // public function addFavory(utilisateur $favory): static
    // {
    //     if (!$this->favories->contains($favory)) {
    //         $this->favories->add($favory);
    //     }

    //     return $this;
    // }

    // public function removeFavory(utilisateur $favory): static
    // {
    //     $this->favories->removeElement($favory);

    //     return $this;
    // }

  
   
}
