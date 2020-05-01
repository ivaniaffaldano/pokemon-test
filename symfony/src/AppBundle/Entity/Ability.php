<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ability
 *
 * @ORM\Table(name="ability", uniqueConstraints={@ORM\UniqueConstraint(name="UNIQ_35CFEE3C5E237E06", columns={"name"})})
 * @ORM\Entity
 */
class Ability
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

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
     * Set name
     *
     * @param string $name
     *
     * @return Ability
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Pokemon", mappedBy="ability")
     */
    private $pokemon;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pokemon = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function addPokemon(\AppBundle\Entity\Pokemon $pokemon)
    {
        $this->pokemons[] = $pokemon;    
        return $this;
    }

}

