<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\OneToOne(targetEntity="User", mappedBy="idParrain")
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @var binary|null
     *
     * @ORM\Column(name="parrain", type="binary", nullable=true)
     */
    private $parrain;

    /**
     * @var binary|null
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $idParrain;

    /**
     * @var binary|null
     *
     * @ORM\Column(type="array", nullable=true)
     */
    private $listeFilleul;


    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    /**
     * Set parrain.
     *
     * @param binary|null $parrain
     *
     * @return User
     */
    public function setParrain($parrain = null)
    {
        $this->parrain = $parrain;

        return $this;
    }

    /**
     * Get parrain.
     *
     * @return binary|null
     */
    public function getParrain()
    {
        return $this->parrain;
    }

    /**
     * Set idParrain.
     *
     * @param int|null $idParrain
     *
     * @return User
     */
    public function setIdParrain($idParrain = null)
    {
        $this->idParrain = $idParrain;

        return $this;
    }

    /**
     * Get idParrain.
     *
     * @return int|null
     */
    public function getIdParrain()
    {
        return $this->idParrain;
    }

    /**
     * Set listeFilleul.
     *
     * @param string|null $listeFilleul
     *
     * @return User
     */
    public function setListeFilleul($listeFilleul = null)
    {
        $this->listeFilleul = $listeFilleul;

        return $this;
    }

    /**
     * Get listeFilleul.
     *
     * @return string|null
     */
    public function getListeFilleul()
    {
        return $this->listeFilleul;
    }
}
