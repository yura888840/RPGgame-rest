<?php
/**
 * Created by PhpStorm.
 * User: yuri
 * Date: 19.07.17
 * Time: 17:49
 */

namespace RPGBundle\Service\Scenario;

use RPGBundle\Entity\QuestSteps;
use RPGBundle\Service\Request\RequestHelper;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class StepQuestionProcessor
{
    /**
     * @var Container
     */
    private $container;

    private $requestHelper;

    /**
     * StepQuestionProcessor constructor.
     * @param Container $container
     */
    public function __construct($container, $requestHelper)
    {
        $this->container     = $container;
        $this->requestHelper = $requestHelper;
    }

    /**
     * Needed by phpunit
     * @param $requestHelper
     */
    public function setRequestHelper($requestHelper)
    {
        $this->requestHelper = $requestHelper;
    }

    /**
     * @param QuestSteps $stepObject
     * @param Request|mixed $request
     * @return mixed|int|string
     * @throws \Exception
     */
    public function processStepQuestionare(QuestSteps $stepObject, $request)
    {
        $data                   = $this->extractDataFromStepObject($stepObject);
        $headers                = $this->requestHelper->extractHeadersFromRequest($request);
        $defaultPreviousStepId  = $stepObject->getDefaultPreviousStepIdForForm();

        if (!array_key_exists('choice', $headers)) {
            return $this->choiceHeaderNotPresent($defaultPreviousStepId);
        }
        $choosen = intval($headers['choice']);

        $choicesMapping = $this->extractAllChoiceValues($data);
        if (!array_key_exists($choosen, $choicesMapping)) {
            return $defaultPreviousStepId;
        }

        $currentChoiceParams = $data[$choicesMapping[$choosen]];

        $handlers = $this->getHandlers($currentChoiceParams);

        foreach ($handlers as $handler) {
            $service = $this->container->get('RPGBundle\Service\Handlers\\' . $handler);
            $service->execute();
        }

        $nextStep = $currentChoiceParams['jump_to_next_node'];
        return $nextStep;
    }

    private function extractAllChoiceValues($data)
    {
        $choicesMapping = [];
        foreach ($data as $k => $v) {
            if (!array_key_exists('value_from_previous_form_node', $v)) {
                continue;
            }

            $choicesMapping[$v['value_from_previous_form_node']] = $k;
        }

        return $choicesMapping;
    }

    /**
     * @param $previousStepId
     * @return mixed
     */
    private function choiceHeaderNotPresent(int $previousStepId) : int
    {
        return $previousStepId;
    }

    /**
     * @param QuestSteps $stepObject
     * @return mixed
     */
    private function extractDataFromStepObject(QuestSteps $stepObject)
    {
        $formDataCollection = $stepObject->getFormDataCollection();
        // @todo check if the format is correct
        return json_decode($formDataCollection, true);
    }

    /**
     * @param $currentChoiceParams
     * @return array
     */
    private function getHandlers($currentChoiceParams)
    {
        if (!array_key_exists('handlersListForThisTransition', $currentChoiceParams)) {
            return [];
        }

        return $currentChoiceParams['handlersListForThisTransition'];
    }
}
