<?php

namespace RPGBundle\Service\Handlers;

/**
 * Created by PhpStorm.
 * User: yuri
 * Date: 19.07.17
 * Time: 18:26
 */
class DecreaseHealth extends Handler implements IHandler
{
    public function execute(&$entity)
    {
        $health = $entity->getHealth();

        if ($health > 1) {
            $entity->setHealth($health - 1);
        }

        return $entity;
    }
}
