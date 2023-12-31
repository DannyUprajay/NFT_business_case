<?php

namespace App\Entity;

use App\Repository\NFTRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: NFTRepository::class)]
class NFT
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['nftall', 'gallery'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['nftall', 'gallery'])]
    private ?int $price = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['nftall', 'gallery'])]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255)]
    #[Groups(['nftall', 'gallery'])]
    private ?string $pathImage = null;

    #[ORM\ManyToOne(inversedBy: 'nfts')]
    #[Groups(['nftall', 'gallery'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToMany(targetEntity: Gallery::class, inversedBy: 'nFTs')]
    #[Groups(['nftall', 'gallery'])]
    private Collection $galleries;

    #[ORM\ManyToMany(targetEntity: SubCategory::class, inversedBy: 'nFTs')]
    #[Groups(['nftall', 'gallery'])]
    private Collection $subCategories;

    #[ORM\Column(length: 255)]
    #[Groups(['nftall', 'gallery'])]
    private ?string $name = null;

    public function __construct()
    {
        $this->galleries = new ArrayCollection();
        $this->subCategories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getPathImage(): ?string
    {
        return $this->pathImage;
    }

    public function setPathImage(string $pathImage): static
    {
        $this->pathImage = $pathImage;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Gallery>
     */
    public function getGalleries(): Collection
    {
        return $this->galleries;
    }

    public function addGallery(Gallery $gallery): static
    {
        if (!$this->galleries->contains($gallery)) {
            $this->galleries->add($gallery);
        }

        return $this;
    }

    public function removeGallery(Gallery $gallery): static
    {
        $this->galleries->removeElement($gallery);

        return $this;
    }

    /**
     * @return Collection<int, SubCategory>
     */
    public function getSubCategories(): Collection
    {
        return $this->subCategories;
    }

    public function addSubCategory(SubCategory $subCategory): static
    {
        if (!$this->subCategories->contains($subCategory)) {
            $this->subCategories->add($subCategory);
        }

        return $this;
    }

    public function removeSubCategory(SubCategory $subCategory): static
    {
        $this->subCategories->removeElement($subCategory);

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }
}
