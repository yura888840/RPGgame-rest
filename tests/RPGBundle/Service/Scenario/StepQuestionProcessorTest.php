<?php
/**
 * Created by PhpStorm.
 * User: yuri
 * Date: 21.07.17
 * Time: 15:47
 */

namespace Tests\RPGBundle\Service\Scenario;

use PHPUnit\Framework\TestCase;
use RPGBundle\Entity\QuestSteps;
use RPGBundle\Service\Request\RequestHelper;
use RPGBundle\Service\Scenario\StepQuestionProcessor;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;

class StepQuestionProcessorTest extends TestCase
{
    /** @var  StepQuestionProcessor */
    private $service;

    private $request;

    public function setUp()
    {
        $container = $this->getMockBuilder(Container::class)->disableOriginalConstructor();

        $requestHelper = $this->getMockBuilder(RequestHelper::class)
            ->disableOriginalConstructor()
            ->getMock();

        $requestHelper->expects($this->any())
            ->method('extractHeadersFromRequest')
            ->withAnyParameters()
            ->will($this->returnValue(['header1' => 1, 'header2' => 2]))
        ;

        $this->service = new StepQuestionProcessor($container, $requestHelper);
        $this->request = $this->getMockBuilder(Request::class)->disableOriginalConstructor();
    }

    public function testRunStepProcessorWithoutAnyChoice()
    {
        $stepObject = new QuestSteps();
        $stepObject->setStepId(2);
        $stepObject->setDefaultPreviousStepIdForForm(1);
        $stepObject->setNodeType('route');
        $stepObject->setNextStepId(0);
        $stepObject->setText('test step');

        $nextStep = $this->service->processStepQuestionare($stepObject, $this->request);

        $this->assertEquals($nextStep, 1);
    }

    public function testRunStepProcessorWithChoice()
    {
        $requestHelper = $this->getMockBuilder(RequestHelper::class)
            ->disableOriginalConstructor()
            ->getMock();

        $requestHelper->expects($this->once())
            ->method('extractHeadersFromRequest')
            ->withAnyParameters()
            ->will($this->returnValue(['header1' => 1, 'header2' => 2, 'choice' => 1]))
        ;

        $this->service->setRequestHelper($requestHelper);

        $stepObject = new QuestSteps();
        $stepObject->setStepId(2);
        $stepObject->setDefaultPreviousStepIdForForm(1);
        $stepObject->setNodeType('route');
        $stepObject->setNextStepId(0);
        $stepObject->setText('test step');
        $stepObject->setFormDataCollection(
            '[{"value_from_previous_form_node": 1, "stepIdFromWhichWasTransition": 5, "parametersFromTheForm":[], "handlersListForThisTransition":[], "jump_to_next_node": 9, "error_message": "Not all parameters specified"},{"value_from_previous_form_node": 2, "stepIdFromWhichWasTransition": 5, "parametersFromTheForm":[], "handlersListForThisTransition":["DecreaseHealth"], "jump_to_next_node": 7, "error_message": "Not all parameters specified"}]'
        );

        $nextStep = $this->service->processStepQuestionare($stepObject, $this->request);
        $this->assertEquals($nextStep, 9);
    }
}