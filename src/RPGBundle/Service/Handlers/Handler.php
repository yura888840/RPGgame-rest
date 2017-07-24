<?php
/**
 * Created by PhpStorm.
 * User: yuri
 * Date: 19.07.17
 * Time: 18:27
 */

namespace RPGBundle\Service\Handlers;

class Handler
{
    protected $characterId;

    public function setCharacter(int $characterId)
    {
        $this->characterId = $characterId;
    }
}
