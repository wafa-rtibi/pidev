<?php
/*
namespace App\Entity;

use App\Repository\AppelAuDonRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AppelAuDonRepository::class)]
class AppelAuDon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }
}
*/

namespace App\Entity;

use App\Repository\AppelAuDonRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AppelAuDonRepository::class)]
class AppelAuDon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\ManyToOne(targetEntity: Dons::class, inversedBy: 'appelsAuDon')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Dons $dons;

    #[ORM\ManyToOne(targetEntity: Organisation::class, inversedBy: 'appelsAuDon')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Organisation $organisation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDons(): ?Dons
    {
        return $this->dons;
    }

    public function setDons(?Dons $dons): self
    {
        $this->dons = $dons;

        return $this;
    }

    public function getOrganisation(): ?Organisation
    {
        return $this->organisation;
    }

    public function setOrganisation(?Organisation $organisation): self
    {
        $this->organisation = $organisation;

        return $this;
    }
}
