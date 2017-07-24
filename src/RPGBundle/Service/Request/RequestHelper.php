<?php

namespace RPGBundle\Service\Request;

use Symfony\Component\HttpFoundation\Request;

/**
 * Created by PhpStorm.
 * User: yuri
 * Date: 21.07.17
 * Time: 16:46
 */
class RequestHelper
{
    /**
     * @param Request $request
     * @return array
     */
    public function extractHeadersFromRequest($request)
    {
        /** @var Request $headers */
        $headers = $request->headers->all();
        foreach ($headers as $k => $header) {
            if (is_array($header)) {
                $headers[$k] = array_shift($header);
            }
        }
        return $headers;
    }
}
