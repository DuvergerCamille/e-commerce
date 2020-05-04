<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommandeProduitRepository")
 */
class CommandeProduit
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Commande", cascade={"persist"})
     */
    private $commande;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Instruments", cascade={"persist"})
     */
    private $instrument;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function setCommande(Commande $commande)
    {
        $this->commande = $commande;
    }

    public function getCommande()
    {
        return $this->commande;
    }

    public function setInstrument(Instruments $instrument)
    {
        $this->instrument = $instrument;
    }

    public function getInstrument()
    {
        return $this->instrument;
    }
}
