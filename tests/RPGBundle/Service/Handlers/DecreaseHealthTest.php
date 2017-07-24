<?php
/**
 * Created by PhpStorm.
 * User: yuri
 * Date: 21.07.17
 * Time: 15:47
 */
namespace Tests\RPGBundle\Service\Handlers;

use PHPUnit\Framework\TestCase;
use RPGBundle\Entity\Role;
use RPGBundle\Service\Handlers\DecreaseHealth;

class DecreaseHealthTest extends TestCase
{
    /** @var  DecreaseHealth */
    private $service;

    public function setUp()
    {
        $this->service = new DecreaseHealth();
    }

    public function testDecreaseHealthInEntity()
    {
        $entity = new Role();
        $entity->setHealth(100);

        $this->service->execute($entity);

        $this->assertEquals($entity->getHealth(), 99);
    }
}