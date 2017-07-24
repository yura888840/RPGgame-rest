<?php
/**
 * Created by PhpStorm.
 * User: yuri
 * Date: 18.07.17
 * Time: 16:33
 */

namespace RPGBundle\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Ramsey\Uuid\Uuid;
use RPGBundle\Entity\PersonSavedState;
use RPGBundle\Service\UserSession;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use RPGBundle\Entity\GameSession;

/**
 * Class GameplayController
 * @package RPGBundle\Controller
 */
class GameplayController extends FOSRestController
{
    /**
     * @ApiDoc(
     *     resource=true,
     *     resourceDescription="Operations with current gameplay.",
     *     description="Start gameplay for user."
     *  )
     *
     * @Rest\Post("/gameplay/start/{characterId}", requirements={"characterId" = "\d+"})
     */
    public function startGameAction(int $characterId)
    {
        /** @var UserSession $sessionService */
        $sessionService = $this->get('user_session');

        try {
            $uuid = $sessionService->startGame($characterId);
        } catch (\Exception $e) {
            return new View(
                [
                    'body' => $e->getMessage(),
                ],
                $e->getCode()
            );
        }

        return new View(
            null,
            Response::HTTP_OK,
            [
                'uuid' => $uuid
            ]
        );
    }

    /**
     * @ApiDoc(
     *     resource=true,
     *     resourceDescription="Operations with current gameplay.",
     *     description="Save gameplay for user."
     *  )
     *
     * @Rest\Post("/gameplay/save/{uuid}")
     */
    public function gameplaySaveAction(string $uuid)
    {
        /** @var UserSession $sessionService */
        $sessionService = $this->get('user_session');

        try {
            $sessionService->saveState($uuid);
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
            null,
            Response::HTTP_OK
        );
    }

    /**
     * @ApiDoc(
     *     resource=true,
     *     resourceDescription="Operations with current gameplay.",
     *     description="Restore gameplay for user."
     *  )
     *
     * @Rest\Post("/gameplay/load/{uuid}")
     */
    public function gameplayRestoreAction($uuid)
    {
        /** @var UserSession $sessionService */
        $sessionService = $this->get('user_session');

        try {
            $sessionService->loadState($uuid);
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
            null,
            Response::HTTP_OK
        );
    }

    /**
     * @ApiDoc(
     *     resource=true,
     *     resourceDescription="Operations with current gameplay.",
     *     description="Restart gameplay for user."
     *  )
     *
     * @Rest\Post("/gameplay/restart/{uuid}")
     */
    public function gameplayRestartAction($uuid)
    {
        /** @var UserSession $sessionService */
        $sessionService = $this->get('user_session');

        try {
            $sessionService->restartGame($uuid);
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
            null,
            Response::HTTP_OK
        );
    }
}
