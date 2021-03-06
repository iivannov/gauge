<?php

namespace Iivannov\Gauge\State;

use Iivannov\Gauge\Contracts\StateResolver;

class UrlStateResolver implements StateResolver
{

    public function enabled()
    {
        return isset($_GET['debug']) && $_GET['debug'] == 'gauge';
    }
}