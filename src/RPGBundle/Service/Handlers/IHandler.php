<?php
/**
 * Created by PhpStorm.
 * User: yuri
 * Date: 19.07.17
 * Time: 18:29
 */

namespace RPGBundle\Service\Handlers;

interface IHandler
{
    public function execute(&$entity);
}
