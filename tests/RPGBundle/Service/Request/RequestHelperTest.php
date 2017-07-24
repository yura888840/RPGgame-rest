<?php

namespace Tests\RPGBundle\Service\Request;

use PHPUnit\Framework\TestCase;
use RPGBundle\Service\Request\RequestHelper;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\Request;

/**
 * Created by PhpStorm.
 * User: yuri
 * Date: 21.07.17
 * Time: 17:24
 */
class RequestHelperTest extends TestCase
{
    /** @var  RequestHelper */
    private $service;

    private $originalHeaders = [
        'header1' => ['val1'],
        'header2' => ['val2']
    ];

    private $expectedValues = [
        'header1' => 'val1',
        'header2' => 'val2'
    ];

    public function setUp()
    {
        $this->service = new RequestHelper();
    }

    public function testExtractHeaders()
    {
        $request = $this
            ->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->getMock();

        $headers = $this
            ->getMockBuilder(HeaderBag::class)
            ->disableOriginalConstructor()
            ->getMock();
        $headers
            ->expects($this->any())
            ->method('all')
            ->withAnyParameters()
            ->will($this->returnValue($this->originalHeaders))
        ;

        $request->headers = $headers;

        $this->assertEquals(
            $this->service->extractHeadersFromRequest($request),
            $this->expectedValues
        );
    }
}