<?php

namespace RPGBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PersonOnMap
 *
 * @ORM\Table(name="person_on_map")
 * @ORM\Entity(repositoryClass="RPGBundle\Repository\PersonOnMapRepository")
 */
class PersonSavedState
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
     * @var int
     *
     * @ORM\Column(name="personId", type="integer", unique=true)
     */
    private $personId;

    /**
     * @var int
     *
     * @ORM\Column(name="stepId", type="integer")
     */
    private $stepId;


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
     * Set personId
     *
     * @param integer $personId
     *
     * @return PersonOnMap
     */
    public function setPersonId($personId)
    {
        $this->personId = $personId;

        return $this;
    }

    /**
     * Get personId
     *
     * @return int
     */
    public function getPersonId()
    {
        return $this->personId;
    }

    /**
     * Set stepId
     *
     * @param integer $stepId
     *
     * @return PersonOnMap
     */
    public function setStepId($stepId)
    {
        $this->stepId = $stepId;

        return $this;
    }

    /**
     * Get stepId
     *
     * @return int
     */
    public function getStepId()
    {
        return $this->stepId;
    }
}
