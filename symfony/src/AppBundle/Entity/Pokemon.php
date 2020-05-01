<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pokemon
 *
 * @ORM\Table(name="pokemon", uniqueConstraints={@ORM\UniqueConstraint(name="UNIQ_62DC90F35E237E06", columns={"name"})})
 * @ORM\Entity
 */
class Pokemon
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="sprite", type="string", length=255, nullable=false)
     */
    private $sprite;

    /**
     * @var int
     *
     * @ORM\Column(name="exp", type="integer")
     */
    private $exp;

    /**
     * Set id
     *
     * @param int $id
     *
     * @return Pokemons
     */
    public function setId($id)
    {
        $this->id = $id;
    }

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
     * @return Pokemons
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
     * Set sprite
     *
     * @param string $sprite
     *
     * @return Pokemons
     */
    public function setSprite($sprite)
    {
        $this->sprite = $sprite;

        return $this;
    }

    /**
     * Get sprite
     *
     * @return string
     */
    public function getSprite()
    {
        return $this->sprite;
    }

    /**
     * Set exp
     *
     * @param string $exp
     *
     * @return Pokemons
     */
    public function setExp($exp)
    {
        $this->exp = $exp;

        return $this;
    }

    /**
     * Get sprite
     *
     * @return string
     */
    public function getExp()
    {
        return $this->exp;
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Ability", inversedBy="pokemon")
     * @ORM\JoinTable(name="pokemon_has_ability",
     *   joinColumns={
     *     @ORM\JoinColumn(name="pokemon_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="ability_id", referencedColumnName="id")
     *   }
     * )
     */
    private $ability;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Type", inversedBy="pokemon")
     * @ORM\JoinTable(name="pokemon_has_type",
     *   joinColumns={
     *     @ORM\JoinColumn(name="pokemon_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="type_id", referencedColumnName="id")
     *   }
     * )
     */
    private $type;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Team", mappedBy="pokemon")
     */
    private $team;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->ability = new \Doctrine\Common\Collections\ArrayCollection();
        $this->type = new \Doctrine\Common\Collections\ArrayCollection();
        $this->team = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function addAbility(\AppBundle\Entity\Ability $ability)
    {
        $ability->addPokemon($this);
        $this->ability[] = $ability;    
        return $this;
    }

    public function addType(\AppBundle\Entity\Type $type)
    {
        $type->addPokemon($this);
        $this->type[] = $type;    
        return $this;
    }

    public function addTeam(\AppBundle\Entity\Team $team)
    {
        $this->team[] = $team;    
        return $this;
    }

    public function getAbility(){
        return $this->ability;
    }

    public function getType(){
        return $this->type;
    }

}

