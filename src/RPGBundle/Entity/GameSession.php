<?php

namespace RPGBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GameSession
 *
 * @ORM\Table(name="game_session")
 * @ORM\Entity(repositoryClass="RPGBundle\Repository\GameSessionRepository")
 */
class GameSession
{
    const EXPIRATION_TIME_INTERVAL = 3600;
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
     * @ORM\Column(name="session_uuid", type="string", length=255, nullable=true)
     */
    private $sessionUUID;

    /**
     * @var int
     *
     * @ORM\Column(name="personId", type="integer", unique=true)
     */
    private $personId;

    /**
     * @var int
     *
     * @ORM\Column(name="stepIdInQuest", type="integer")
     */
    private $stepIdInQuest;

    /**
     * @var string
     *
     * @ORM\Column(name="data", type="string", length=255, nullable=true)
     */
    private $data;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="lastUpdated", type="datetimetz", nullable=true)
     */
    private $lastUpdated;

    /**
     * GameSession constructor.
     */
    public function __construct()
    {
        $this->lastUpdated = new \DateTime();
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
     * Set personId
     *
     * @param integer $personId
     *
     * @return GameSession
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
     * Set stepIdInQuest
     *
     * @param integer $stepIdInQuest
     *
     * @return GameSession
     */
    public function setStepIdInQuest($stepIdInQuest)
    {
        $this->stepIdInQuest = $stepIdInQuest;

        return $this;
    }

    /**
     * Get stepIdInQuest
     *
     * @return int
     */
    public function getStepIdInQuest()
    {
        return $this->stepIdInQuest;
    }

    /**
     * Set data
     *
     * @param string $data
     *
     * @return GameSession
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get data
     *
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function getSessionUUID()
    {
        return $this->sessionUUID;
    }

    /**
     * @param string $sessionUUID
     */
    public function setSessionUUID($sessionUUID)
    {
        $this->sessionUUID = $sessionUUID;
    }

    /**
     * Set lastUpdated
     *
     * @param \DateTime $lastUpdated
     *
     * @return Test
     */
    public function setLastUpdated($lastUpdated)
    {
        $this->lastUpdated = $lastUpdated;

        return $this;
    }

    /**
     * Get lastUpdated
     *
     * @return \DateTime
     */
    public function getLastUpdated()
    {
        return $this->lastUpdated;
    }

    public function isExpired()
    {
        $currentMoment = new \DateTime();
        if ($currentMoment->diff($this->lastUpdated)->format('s') > self::EXPIRATION_TIME_INTERVAL) {
            return true;
        }

        return false;
    }
}
