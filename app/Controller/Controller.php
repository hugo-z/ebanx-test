<?php

namespace Ebanx\Controller;

use Ebanx\Libs\Request;

class Controller
{
    protected Request $request;

    public function __construct()
    {
        $this->request = new Request();
    }
}