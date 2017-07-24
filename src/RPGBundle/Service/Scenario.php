<?php
/**
 * Created by PhpStorm.
 * User: yuri
 * Date: 21.07.17
 * Time: 13:54
 */

namespace RPGBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use RPGBundle\Entity\GameSession;
use RPGBundle\Entity\QuestSteps;
use RPGBundle\Service\Scenario\StepQuestionProcessor;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Scenario
{
    const YOU_FINISHED_THE_QUEST_MESSAGE = "You finished the quest";
    /**
     * @var Registry
     */
    private $doctrine;

    /**
     * @var StepQuestionProcessor
     */
    private $stepProcessor;

    /**
     * @var GameSession
     */
    private $sessionUserData;

    public function __construct(Registry $doctrine, StepQuestionProcessor $stepProcessor)
    {
        $this->doctrine = $doctrine;
        $this->stepProcessor = $stepProcessor;
    }

    /**
     * @param GameSession $sessionUserData
     * @return array
     * @throws \Exception
     */
    public function doCurrentStep(GameSession $sessionUserData)
    {
        try {
            $characterId = $sessionUserData->getPersonId();
            $this->loadUserSessionData($characterId);

            $currentStep = $this->getCurrentStep($sessionUserData);
            $this->saveInSessionNextStepAsCurrent($currentStep->getId());
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

        return [
            $currentStep->getText(),
            []
        ];
    }

    /**
     * @param GameSession $sessionUserData
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function doNextStep(GameSession $sessionUserData, Request $request)
    {
        try {
            $characterId = $sessionUserData->getPersonId();
            $this->loadUserSessionData($characterId);

            /** @var QuestSteps $currentStep */
            $currentStep = $this->getCurrentStep($sessionUserData);
            $stepId = $currentStep->getId();

            /** @var QuestSteps $nextStep */
            $nextStep = $this->loadStepData($currentStep->getNextStepId());
            $stepId = $nextStep->getId();

            if ($nextStep->isRoute()) {
                $stepId = $this->handleRouting($nextStep, $request);
                $nextStep = $this->loadStepData($stepId);
            }

            $this->saveInSessionNextStepAsCurrent($stepId);

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

        return [
            $nextStep->getText(),
            []
        ];
    }

    private function saveInSessionNextStepAsCurrent($stepId)
    {
        /** @var EntityManager $em */
        $em = $this->doctrine->getManager();

        $this->sessionUserData->setStepIdInQuest($stepId);
        $this->sessionUserData->setLastUpdated(new \DateTime());
        $em->persist($this->sessionUserData);
        $em->flush();
    }

    private function handleRouting($nextStep, $request)
    {
        $stepId = $this->stepProcessor->processStepQuestionare($nextStep, $request);

        if ($stepId) {
            //revert state to previous node
        }

        // merging messages
        return $stepId;
    }

    /**
     * @param GameSession $sessionUserData
     * @return QuestSteps
     */
    private function getCurrentStep(GameSession $sessionUserData)
    {

        $stepId = $this->sessionUserData->getStepIdInQuest();
        $currentStep = $this->loadStepData($stepId);

        return $currentStep;
    }

    private function loadUserSessionData(int $characterId)
    {
        /** @var GameSession $sessionUserData */
        $this->sessionUserData = $this
            ->doctrine
            ->getRepository('RPGBundle:GameSession')
            ->findOneBy(['personId' => $characterId]);

        if (!$this->sessionUserData) {
            throw new \RuntimeException(
                'User game session not found. Please start game.',
                Response::HTTP_NOT_FOUND
            );
        }
    }

    private function loadStepData(int $stepId)
    {
        $stepData = $this
            ->doctrine
            ->getRepository('RPGBundle:QuestSteps')
            ->find($stepId);

        if ($stepData === null) {
            throw new \RuntimeException(
                self::YOU_FINISHED_THE_QUEST_MESSAGE,
                Response::HTTP_OK
            );
        }

        return $stepData;
    }
}
