<?php

namespace MagasinBundle\Entity;

/**
 * Categorie
 */
class Categorie
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $nom;

    /**
     * @var int
     */
    private $idparent;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Categorie
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set idparent
     *
     * @param integer $idparent
     *
     * @return Categorie
     */
    public function setIdparent($idparent)
    {
        $this->idparent = $idparent;

        return $this;
    }

    /**
     * Get idparent
     *
     * @return int
     */
    public function getIdparent()
    {
        return $this->idparent;
    }
}

