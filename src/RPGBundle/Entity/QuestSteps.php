<?php

namespace RPGBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * QuestSteps
 *
 * @ORM\Table(name="quest_steps")
 * @ORM\Entity(repositoryClass="RPGBundle\Repository\QuestStepsRepository")
 */
class QuestSteps
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
     * @ORM\Column(name="stepId", type="integer", unique=true)
     */
    private $stepId;

    /**
     * @var string
     *
     * @ORM\Column(name="nodeType", type="text")
     */
    private $nodeType = 'normal';

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="text")
     */
    private $text;

    /**
     * @var string
     * JSON_encoded array of format :
     * [
     *      [
     *          'value_from_previous_form_node' => <int_val>,
     *          'stepIdFromWhichWasTransition' => <int_val>,
     *          'parametersFromTheForm' => [<param1>, <param2>, ..],
     *          'handlersListForThisTransition' => [<handler1>, <handler2>, ..],
     *          'jump_to_next_node' => <int_val>  (next step Id)
     *          'error_message' => <string>
     *      ],
     *      ...
     * ]
     *
     * @ORM\Column(name="formDataCollection", type="text")
     */
    private $formDataCollection;

    /**
     * @var string
     *
     * @ORM\Column(name="defaultPreviousStepIdForForm", type="integer")
     */
    private $defaultPreviousStepIdForForm;

    /**
     * @var string
     *
     * @ORM\Column(name="nextStepId", type="integer")
     */
    private $nextStepId;

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
     * Set stepId
     *
     * @param integer $stepId
     *
     * @return QuestSteps
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

    /**
     * Set text
     *
     * @param string $text
     *
     * @return QuestSteps
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set nextStepId
     *
     * @param int $nextStepId
     *
     * @return QuestSteps
     */
    public function setNextStepId($nextStepId)
    {
        $this->nextStepId = $nextStepId;

        return $this;
    }

    /**
     * Get nextStepId
     *
     * @return int
     */
    public function getNextStepId()
    {
        return $this->nextStepId;
    }

    /**
     * @return string
     */
    public function getFormDataCollection()
    {
        return $this->formDataCollection;
    }

    /**
     * @param string $formDataCollection
     */
    public function setFormDataCollection($formDataCollection)
    {
        $this->formDataCollection = $formDataCollection;
    }

    /**
     * @return string
     */
    public function getDefaultPreviousStepIdForForm()
    {
        return $this->defaultPreviousStepIdForForm;
    }

    /**
     * @param string $defaultPreviousStepIdForForm
     */
    public function setDefaultPreviousStepIdForForm($defaultPreviousStepIdForForm)
    {
        $this->defaultPreviousStepIdForForm = $defaultPreviousStepIdForForm;
    }

    /**
     * @return string
     */
    public function getNodeType()
    {
        return $this->nodeType;
    }

    /**
     * @param string $nodeType
     */
    public function setNodeType($nodeType)
    {
        $this->nodeType = $nodeType;
    }

    public function isRoute()
    {
        return $this->nodeType == 'normal' ? false : true;
    }
}
