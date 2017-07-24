<?php
/**
 * Created by PhpStorm.
 * User: yuri
 * Date: 18.07.17
 * Time: 12:21
 */

namespace RPGBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use RPGBundle\Entity\Person;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Class PersonController
 * @package RPGBundle\Controller
 */
class PersonController extends FOSRestController
{
    /**
     * @ApiDoc(
     *     resource=true,
     *     resourceDescription="Operations on characters.",
     *     description="Retrieve list of characters."
     *  )
     *
     * @Rest\Get("/person")
     */
    public function getAction()
    {
        $restresult = $this->getDoctrine()->getRepository('RPGBundle:Person')->findAll();
        if ($restresult === null) {
            return new View("there are no users exist", Response::HTTP_NOT_FOUND);
        }
        return $restresult;
    }

    /**
     * @ApiDoc(
     *     resource=true,
     *     resourceDescription="Operations on characters.",
     *     description="Get character information by Id."
     *  )
     *
     * @Rest\Get("/person/{id}", requirements={"characterId" = "\d+"})
     */
    public function idAction($id)
    {
        $singleresult = $this->getDoctrine()->getRepository('RPGBundle:Person')->find($id);
        if ($singleresult === null) {
            return new View("Person not found", Response::HTTP_NOT_FOUND);
        }
        return $singleresult;
    }

    /**
     * @ApiDoc(
     *     resource=true,
     *     resourceDescription="Operations on characters.",
     *     description="Create new character"
     *  )
     *
     * @Rest\Post("/person")
     */
    public function createAction(Request $request)
    {
        $data = new Person;
        $name = $request->get('name');
        $role = $request->get('role');
        if (empty($name) || empty($role)) {
            return new View("NULL VALUES ARE NOT ALLOWED: name, role", Response::HTTP_NOT_ACCEPTABLE);
        }

        /** @var Role $persistentRole */
        $persistentRole = $this->getDoctrine()->getRepository('RPGBundle:Role')->findOneBy(['name' => $role]);
        if ($persistentRole === null) {
            return new View(
                [
                    "body" => "Role not found"
                ],
                Response::HTTP_NOT_ACCEPTABLE
            );
        }

        $uuid = $persistentRole->getName();

        $data->setName($name);
        $data->setRole($role);// hack
        $data->setUuid($uuid);
        $em = $this->getDoctrine()->getManager();
        $em->persist($data);
        $em->flush();

        $headers = [
            'id' => $data->getId()
        ];
        return new View(null, Response::HTTP_CREATED, $headers);
    }

    /**
     * @ApiDoc(
     *     resource=true,
     *     resourceDescription="Operations on characters.",
     *     description="Update data (parameters) of existing character (Looks like system call)"
     *  )
     *
     * @Rest\Put("/person/{id}", requirements={"id" = "\d+"})
     */
    public function updateAction($id, Request $request)
    {
        $name = $request->get('name');
        $role = $request->get('role');
        $sn = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()->getRepository('RPGBundle:Person')->find($id);
        if (empty($user)) {
            return new View("Person not found", Response::HTTP_NOT_FOUND);
        } elseif (!empty($name) && !empty($role)) {
            $user->setName($name);
            $user->setRole($role);
            $sn->flush();
            return new View("Person Updated Successfully", Response::HTTP_OK);
        } elseif (empty($name) && !empty($role)) {
            $user->setRole($role);
            $sn->flush();
            return new View("role Updated Successfully", Response::HTTP_OK);
        } elseif (!empty($name) && empty($role)) {
            $user->setName($name);
            $sn->flush();
            return new View("Person Name Updated Successfully", Response::HTTP_OK);
        } else {
            return new View("User name or role cannot be empty", Response::HTTP_NOT_ACCEPTABLE);
        }
    }

    /**
     * @ApiDoc(
     *     resource=true,
     *     resourceDescription="Operations on characters.",
     *     description="Delete existing character. Dangerous operation"
     *  )
     *
     * @Rest\Delete("/person/{id}", requirements={"id" = "\d+"})
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()->getRepository('RPGBundle:Person')->find($id);
        if (empty($user)) {
            return new View("Person not found", Response::HTTP_NOT_FOUND);
        } else {
            $em->remove($user);
            $em->flush();
        }
        return new View("deleted successfully", Response::HTTP_OK);
    }
}
