<?php
/**
 * Created by PhpStorm.
 * User: yuri
 * Date: 18.07.17
 * Time: 16:38
 */

namespace RPGBundle\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use FOS\RestBundle\Controller\FOSRestController;
use RPGBundle\Service\Scenario;
use RPGBundle\Service\Scenario\StepQuestionProcessor;
use RPGBundle\Service\UserSession;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use RPGBundle\Entity\GameSession;
use RPGBundle\Entity\QuestSteps;

/**
 * Class ScenarioController
 * @package RPGBundle\Controller
 */
class ScenarioController extends FOSRestController
{
    /**
     * @ApiDoc(
     *     resource=true,
     *     resourceDescription="Operations with steps in gameplay.",
     *     description="Get current step for the user."
     *  )
     *
     * @Rest\Get("/scenario/current_step/{uuid}")
     *
     * @param Request $request
     * @param string $uuid
     * @return View
     * @throws \Exception
     */
    public function scenarioCurrentStepAction(Request $request, string $uuid)
    {
        /** @var UserSession $sessionService */
        $sessionService = $this->get('user_session');
        /** @var Scenario $scenarioService */
        $scenarioService = $this->get('scenario');

        try {
            /** @var GameSession $sessionUserData */
            $sessionUserData = $sessionService->getSessionDataIfValid($uuid);

            list($text, $options) = $scenarioService->doCurrentStep($sessionUserData);
        } catch (\RuntimeException $e) {
            return new View(
                [
                    'body' => $e->getMessage(),
                    'event' => '** Not implemented'
                ],
                $e->getCode()
            );
        }

        return new View(
            [
                'body' => $text,
                'options' => $options
            ]
        );
    }

    /**
     * @ApiDoc(
     *     resource=true,
     *     resourceDescription="Operations with steps in gameplay.",
     *     description="Load next step for the user."
     *  )
     *
     * @Rest\Post("/scenario/goahead/{uuid}")
     *
     * @param $uuid
     * @return View
     */
    public function scenarioGoaheadAction(Request $request, $uuid)
    {
        /** @var UserSession $sessionService */
        $sessionService = $this->get('user_session');
        /** @var Scenario $scenarioService */
        $scenarioService = $this->get('scenario');

        try {
            /** @var GameSession $sessionUserData */
            $sessionUserData = $sessionService->getSessionDataIfValid($uuid);

            list($text, $options) = $scenarioService->doNextStep($sessionUserData, $request);
        } catch (\Exception $e) {
            return new View(
                [
                    'body' => $e->getMessage(),
                    'event' => '** Not implemented'
                ],
                $e->getCode()
            );
        }

        return new View(
            [
                'body' => $text,
                'options' => $options
            ]
        );
    }
}
