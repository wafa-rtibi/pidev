<?php

namespace App\Entity;

use App\Repository\OffreRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;




#[ORM\Entity(repositoryClass: OffreRepository::class)]
/**
 * @Vich\Uploadable
 */
#[Vich\Uploadable]


class Offre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;


    #[ORM\Column()]
    #[Assert\Length(max: 10, maxMessage: "Your title contains more than 10 characters.")]
    #[Assert\NotBlank(message: "Title is Required ")]
    private ?string $nom;

    #[ORM\Column(length: 255)]
    private ?string $categorie;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Description is Required ")]
    #[Assert\Length(min: 20, minMessage: "Your description does not contain 20 characters.")]
    private ?string $description;

    #[ORM\Column(length: 255)]
    private ?string $etat = "publié";

    #[ORM\Column()]
    private ?DateTime $date_publication;

    /**
     * @Vich\UploadableField(mapping="offres_images", fileNameProperty="images")
     * @var File
     */

    #[Vich\UploadableField(mapping: "offres_images", fileNameProperty: "image1")]
   #[Assert\NotBlank(message: "Select a picture for your offer")] 
    private $imageFile1;

    /**
     * @Vich\UploadableField(mapping="offres_images", fileNameProperty="images")
     * @var File
     */

    #[Vich\UploadableField(mapping: "offres_images", fileNameProperty: "image2")]
    private $imageFile2 = null;


    /**
     * @Vich\UploadableField(mapping="offres_images", fileNameProperty="images")
     * @var File
     */
    #[Vich\UploadableField(mapping: "offres_images", fileNameProperty: "image3")]
    private $imageFile3 = null;

    #[ORM\Column(nullable: true)]
   
    private $image1 = null;

    #[ORM\Column(nullable: true)]
    private ?string $image2 = null;

    #[ORM\Column(nullable: true)]
    private ?string $image3 = null;

    #[ORM\ManyToOne(inversedBy: 'offres')]
    private ?Utilisateur $offreur;



    #[ORM\OneToMany(targetEntity: DemandeOffre::class, mappedBy: 'offre')]
    private Collection $demandes;

    #[ORM\ManyToMany(targetEntity: Utilisateur::class, inversedBy: 'favoris_offres')]
    private Collection $favoris;

   

    public function __construct()
    {
        $this->demandes = new ArrayCollection();
        $this->favoris = new ArrayCollection();
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

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
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

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): static
    {
        $this->etat = $etat;

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
    public function getImageFile1()
    {
        return $this->imageFile1;
    }

    public function setImageFile1(File $imageFile)
    {
        $this->imageFile1 = $imageFile;
    }

    public function getImageFile2()
    {
        return $this->imageFile2;
    }

    public function setImageFile2(File $imageFile)
    {
        $this->imageFile2 = $imageFile;
    }
    public function getImageFile3()
    {
        return $this->imageFile3;
    }

    public function setImageFile3(File $imageFile)
    {
        $this->imageFile3 = $imageFile;
    }
    public function getImage1()
    {
        return $this->image1;
    }

    public function setImage1($image1): static
    {
        $this->image1 = $image1;

        return $this;
    }

    public function getImage2()
    {
        return $this->image2;
    }

    public function setImage2($image2)
    {
        $this->image2 = $image2;

        return $this;
    }
    public function getImage3()
    {
        return $this->image3;
    }

    public function setImage3($image3): static
    {
        $this->image3 = $image3;

        return $this;
    }



    public function getOffreur(): ?Utilisateur
    {
        return $this->offreur;
    }

    public function setOffreur(?Utilisateur $offreur): static
    {
        $this->offreur = $offreur;

        return $this;
    }



    /**
     * @return Collection<int, DemandeOffre>
     */
    public function getDemandes(): Collection
    {
        return $this->demandes;
    }

    public function addDemande(DemandeOffre $demande): static
    {
        if (!$this->demandes->contains($demande)) {
            $this->demandes->add($demande);
            $demande->setOffre($this);
        }

        return $this;
    }

    public function removeDemande($demande): static
    {
        if ($this->demandes->removeElement($demande)) {
            // set the owning side to null (unless already changed)
            if ($demande->getOffre() === $this) {
                $demande->setOffre(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Utilisateur>
     */
    public function getFavoris(): Collection
    {
        return $this->favoris;
    }

    public function addFavori(Utilisateur $favori): static
    {
        if (!$this->favoris->contains($favori)) {
            $this->favoris->add($favori);
        }

        return $this;
    }

    public function removeFavori(Utilisateur $favori): static
    {
        $this->favoris->removeElement($favori);

        return $this;
    }

}
