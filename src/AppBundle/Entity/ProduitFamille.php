<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProduitFamille
 *
 * @ORM\Table(name="produit_famille")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProduitFamilleRepository")
 */
class ProduitFamille
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=100)
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity="Produits", mappedBy="famille")
     */
    private $produits;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set libelle.
     *
     * @param string $libelle
     *
     * @return ProduitFamille
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle.
     *
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->produits = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add produit.
     *
     * @param \AppBundle\Entity\Produits $produit
     *
     * @return ProduitFamille
     */
    public function addProduit(\AppBundle\Entity\Produits $produit)
    {
        $this->produits[] = $produit;

        return $this;
    }

    /**
     * Remove produit.
     *
     * @param \AppBundle\Entity\Produits $produit
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeProduit(\AppBundle\Entity\Produits $produit)
    {
        return $this->produits->removeElement($produit);
    }

    /**
     * Get produits.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProduits()
    {
        return $this->produits;
    }
}
