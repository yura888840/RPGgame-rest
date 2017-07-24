<?php
/**
 * Created by PhpStorm.
 * User: yuri
 * Date: 18.07.17
 * Time: 15:01
 */

namespace RPGBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;
use RPGBundle\Entity\Role;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class RoleController extends FOSRestController
{
    /**
     * @ApiDoc(
     *     resource=true,
     *     resourceDescription="Operations on character's role.",
     *     description="Retrieve list of character's roles."
     *  )
     *
     * @Rest\Get("/role")
     */
    public function getAction()
    {
        $restresult = $this->getDoctrine()->getRepository('RPGBundle:Role')->findAll();
        if (empty($restresult)) {
            return new View("there are no role exist", Response::HTTP_NOT_FOUND);
        }
        return $restresult;
    }

    /**
     * @ApiDoc(
     *     resource=true,
     *     resourceDescription="Operations on character's role.",
     *     description="ADMIN: Create new role."
     *  )
     *
     * @Rest\Post("/role")
     */
    public function createAction(Request $request)
    {
        /** @var Role $data */
        $data = new Role;
        $name       = $request->get('name');
        $health     = $request->get('health');
        $strength   = $request->get('strength');
        $experience = $request->get('experience');
        if (empty($name)
            || empty($health)
            || empty($strength)
            || empty($experience)
        ) {
            return new View(
                "NULL VALUES ARE NOT ALLOWED: name, health, strength, experience",
                Response::HTTP_NOT_ACCEPTABLE
            );
        }

        $data->setName($name);
        $data->setExperience($experience);
        $data->setHealth($health);
        $data->setStrength($strength);

        try {
            $em = $this->getDoctrine()->getManager();
            $em->persist($data);
            $em->flush();
        } catch (\Exception $e) {
            return new View(
                "Error: " . $e->getMessage(),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return new View("Role Added Successfully", Response::HTTP_OK);
    }

    /**
     * @ApiDoc(
     *     resource=true,
     *     resourceDescription="Operations on character's role.",
     *     description="ADMIN: Update existing role."
     *  )
     *
     * @Rest\Put("/role/{id}", requirements={"id" = "\d+"})
     */
    public function updateAction($id, Request $request)
    {
        $name       = $request->get('name');
        $health     = $request->get('health');
        $strength   = $request->get('strength');
        $experience = $request->get('experience');

        try {
            /** @var \Doctrine\Common\Persistence\ObjectManager $em */
            $em = $this->getDoctrine()->getManager();

            /** @var Role $role */
            $role = $this->getDoctrine()->getRepository('RPGBundle:Role')->find($id);
            if (empty($role)) {
                return new View("Person not found", Response::HTTP_NOT_FOUND);
            }
            if (empty($name)
                && empty($health)
                && empty($strength)
                && empty($experience)
            ) {
                return new View(
                    "You should specify one of the following: name, health, strength, experience",
                    Response::HTTP_NOT_ACCEPTABLE
                );
            }

            if (!empty($name)) {
                $role->setName($name);
            }
            if (!empty($health)) {
                $role->setHealth($health);
            }
            if (!empty($strength)) {
                $role->setStrength($health);
            }
            if (!empty($experience)) {
                $role->setExperience($experience);
            }

            $em->flush();
            return new View("Role Updated Successfully", Response::HTTP_OK);
        } catch (\Exception $e) {
            return new View('ERROR: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @ApiDoc(
     *     resource=true,
     *     resourceDescription="Operations on character's role.",
     *     description="ADMIN: Delete role."
     *  )
     *
     * @Rest\Delete("/role/{id}", requirements={"id" = "\d+"})
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $role = $this->getDoctrine()->getRepository('RPGBundle:Role')->find($id);
        if (empty($role)) {
            return new View("Role not found", Response::HTTP_NOT_FOUND);
        } else {
            $em->remove($role);
            $em->flush();
        }
        return new View("deleted successfully", Response::HTTP_OK);
    }
}
