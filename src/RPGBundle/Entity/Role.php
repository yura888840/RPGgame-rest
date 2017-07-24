<?php

namespace RPGBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * Role
 *
 * @ORM\Table(name="role")
 * @ORM\Entity(repositoryClass="RPGBundle\Repository\RoleRepository")
 * @ExclusionPolicy("all")
 */
class Role
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Exclude
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     * @Expose
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="health", type="integer")
     * @Expose
     */
    private $health;

    /**
     * @var int
     *
     * @ORM\Column(name="strength", type="integer")
     * @Expose
     */
    private $strength;

    /**
     * @var int
     *
     * @ORM\Column(name="experience", type="integer")
     * @Expose
     */
    private $experience;

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
     * @return Role
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
     * Set health
     *
     * @param integer $health
     *
     * @return Role
     */
    public function setHealth($health)
    {
        $this->health = $health;

        return $this;
    }

    /**
     * Get health
     *
     * @return int
     */
    public function getHealth()
    {
        return $this->health;
    }

    /**
     * Set strength
     *
     * @param integer $strength
     *
     * @return Role
     */
    public function setStrength($strength)
    {
        $this->strength = $strength;

        return $this;
    }

    /**
     * Get strength
     *
     * @return int
     */
    public function getStrength()
    {
        return $this->strength;
    }

    /**
     * Set experience
     *
     * @param integer $experience
     *
     * @return Role
     */
    public function setExperience($experience)
    {
        $this->experience = $experience;

        return $this;
    }

    /**
     * Get experience
     *
     * @return int
     */
    public function getExperience()
    {
        return $this->experience;
    }
}
