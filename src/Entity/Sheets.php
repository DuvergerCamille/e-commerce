<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Component\Collection\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SheetsRepository")
 */
class Sheets
{
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Categories", cascade={"persist"})
     */
    private $categories;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;

    public function __construct()
    {
      $this->categories = new ArrayCollection();
    }

    public function addCategory(Categories $category)
    {
      $this->categories[] = $category;
    }

    public function removeCategory(Categories $category)
    {
      $this->categories->removeElement($category);
    }

    public function getCategories()
    {
      return $this->categories;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }
}
