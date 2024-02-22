<?php

namespace App\Entity;
use App\Repository\AdministrateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Cache\Adapter\AbstractAdapter;

#[ORM\Entity(repositoryClass: AdministrateurRepository::class)]
class Administrateur extends Utilisateur
{
    #[ORM\OneToMany(mappedBy: 'admin', targetEntity: Reponse::class)]
    private Collection $reponses;

    public function __construct()
    {
        parent::__construct();
        $this->reponses = new ArrayCollection();
    }

    /**
     * @return Collection<int, Reponse>
     */
    public function getReponses(): Collection
    {
        return $this->reponses;
    }

    public function addReponse(Reponse $reponse): static
    {
        if (!$this->reponses->contains($reponse)) {
            $this->reponses->add($reponse);
            $reponse->setAdmin($this);
        }

        return $this;
    }

    public function removeReponse(Reponse $reponse): static
    {
        if ($this->reponses->removeElement($reponse)) {
            // set the owning side to null (unless already changed)
            if ($reponse->getAdmin() === $this) {
                $reponse->setAdmin(null);
            }
        }

        return $this;
    }
}
