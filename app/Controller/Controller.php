<?php

namespace Ebanx\Controller;

use Ebanx\Libs\Request;
use JetBrains\PhpStorm\Pure;

class Controller
{
    protected Request $request;

    #[Pure]
    public function __construct()
    {
        $this->request = new Request();
    }
}