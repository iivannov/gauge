<?php

namespace Iivannov\Gauge\State;

use Iivannov\Gauge\Contracts\StateResolver;

class UrlStateResolver implements StateResolver
{

    public function enabled()
    {
        return getenv('APP_DEBUG') && isset($_GET['debug']) && $_GET['debug'] == 'gauge';
    }
}