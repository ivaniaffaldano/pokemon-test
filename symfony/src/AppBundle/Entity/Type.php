<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Type
 *
 * @ORM\Table(name="type", uniqueConstraints={@ORM\UniqueConstraint(name="UNIQ_8CDE57295E237E06", columns={"name"})})
 * @ORM\Entity
 */
class Type
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
     * @return Type
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
     * @ORM\ManyToMany(targetEntity="Pokemon", mappedBy="type")
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
        $this->pokemon[] = $pokemon;    
        return $this;
    }

}

