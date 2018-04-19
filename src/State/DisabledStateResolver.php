<?php

namespace Iivannov\Gauge\State;

use Iivannov\Gauge\Contracts\StateResolver;

class DisabledStateResolver implements StateResolver
{

    public function enabled()
    {
        return false;
    }
}