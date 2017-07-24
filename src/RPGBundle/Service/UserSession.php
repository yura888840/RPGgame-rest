<?php
/**
 * Created by PhpStorm.
 * User: yuri
 * Date: 21.07.17
 * Time: 18:22
 */

namespace RPGBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use Ramsey\Uuid\Uuid;
use RPGBundle\Entity\GameSession;
use RPGBundle\Entity\PersonSavedState;
use Symfony\Component\HttpFoundation\Response;

class UserSession
{
    private $doctrine;

    const DEFAULT_STEP = 1;

    const SESSION_TTL_MIN = 59;

    public function __construct(Registry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * @param $characterId
     * @return string
     */
    public function startGame($characterId)
    {
        try {
            $person = $this
                ->doctrine
                ->getRepository('RPGBundle:Person')
                ->find($characterId);

            if ($person === null) {
                throw new \RuntimeException("Person not found", Response::HTTP_NOT_FOUND);
            }
            $stepId = self::DEFAULT_STEP;
            /** @var PersonSavedState $savedState */
            $savedState = $this
                ->doctrine
                ->getRepository('RPGBundle:PersonSavedState')
                ->findOneBy(['personId' => $characterId]);

            if ($savedState) {
                $stepId = $savedState->getStepId();
            }

            /** @var GameSession $gameSession */
            $gameSession = $this
                ->doctrine
                ->getRepository('RPGBundle:GameSession')
                ->findOneBy(['personId' => $characterId]);

            if ($gameSession === null) {
                $gameSession = new GameSession();
                $gameSession->setPersonId($characterId);
            }

            $gameSession->setLastUpdated(new \DateTime());
            $gameSession->setStepIdInQuest($stepId);
            $uuid = Uuid::getFactory()->uuid1()->getHex();
            $gameSession->setSessionUUID($uuid);

            /** @var EntityManager $em */
            $em = $this->doctrine->getManager();

            $em->persist($gameSession);
            $em->flush();
        } catch (\Exception $e) {
            $code = $e->getCode();
            if (!in_array(
                $code,
                [
                    Response::HTTP_NOT_FOUND,
                    Response::HTTP_NOT_ACCEPTABLE,
                    Response::HTTP_OK
                ]
            )) {
                $code = Response::HTTP_INTERNAL_SERVER_ERROR;
            }
            throw new \Exception($e->getMessage(), $code);
        }

        return $uuid;
    }

    public function saveState(string $uuid)
    {
        /** @var GameSession $gameSession */
        $gameSession = $this
            ->doctrine
            ->getRepository('RPGBundle:GameSession')
            ->findOneBy(['sessionUUID' => $uuid]);

        if ($gameSession === null) {
            throw new \RuntimeException(
                "Game Session not found. Please start game",
                Response::HTTP_NOT_FOUND
            );
        }
        $gameSession->setLastUpdated(new \DateTime());

        $personId = $gameSession->getPersonId();
        $stepId = $gameSession->getStepIdInQuest();

        $savedState = $this
            ->doctrine
            ->getRepository('RPGBundle:PersonSavedState')
            ->findOneBy(['personId' => $personId]);

        if ($savedState === null) {
            $savedState = new PersonSavedState();
            $savedState->setPersonId($personId);
        }

        $savedState->setStepId($stepId);

        /** @var EntityManager $em */
        $em = $this->doctrine->getManager();

        $em->persist($savedState);
        $em->persist($gameSession);
        $em->flush();
    }

    public function loadState($uuid)
    {
        /** @var GameSession $gameSession */
        $gameSession = $this
            ->doctrine
            ->getRepository('RPGBundle:GameSession')
            ->findOneBy(['sessionUUID' => $uuid]);

        if ($gameSession === null) {
            throw new \RuntimeException(
                "Game Session not found. Please start game",
                Response::HTTP_NOT_FOUND
            );
        }

        $personId = $gameSession->getPersonId();

        $savedState = $this
            ->doctrine
            ->getRepository('RPGBundle:PersonSavedState')
            ->findOneBy(['personId' => $personId]);

        if ($savedState === null) {
            throw new \RuntimeException(
                "You should firstly save game.",
                Response::HTTP_NOT_FOUND
            );
        }

        $stepId = $savedState->getStepId();

        $gameSession->setStepIdInQuest($stepId);
        $gameSession->setLastUpdated(new \DateTime());
        /** @var EntityManager $em */
        $em = $this->doctrine->getManager();

        $em->persist($gameSession);
        $em->flush();
    }

    public function restartGame($uuid)
    {
        /** @var GameSession $gameSession */
        $gameSession = $this
            ->doctrine
            ->getRepository('RPGBundle:GameSession')
            ->findOneBy(['sessionUUID' => $uuid]);

        if ($gameSession === null) {
            throw new \RuntimeException(
                "Game Session not found. Please start game",
                Response::HTTP_NOT_FOUND
            );
        }
        $gameSession->setStepIdInQuest(self::DEFAULT_STEP);
        $gameSession->setLastUpdated(new \DateTime());

        /** @var EntityManager $em */
        $em = $this->doctrine->getManager();

        $em->persist($gameSession);
        $em->flush();
    }

    public function getSessionDataIfValid($uuid)
    {
        /** @var GameSession $gameSession */
        $gameSession = $this
            ->doctrine
            ->getRepository('RPGBundle:GameSession')
            ->findOneBy(['sessionUUID' => $uuid]);

        if ($gameSession === null) {
            throw new \RuntimeException(
                "Game Session not found. Please enter game",
                Response::HTTP_NOT_FOUND
            );
        }

        /** @var \DateTime $lastUpdated */
        $lastUpdated = $gameSession->getLastUpdated();

        $currentTS = new \DateTime();
        if ($currentTS->diff($lastUpdated)->i > self::SESSION_TTL_MIN) {
            throw new \RuntimeException(
                "Game Session expired. Please re-start game",
                Response::HTTP_NOT_FOUND
            );
        }

        return $gameSession;
    }
}
