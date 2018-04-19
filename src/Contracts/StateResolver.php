<?php

namespace Iivannov\Gauge\Contracts;

interface StateResolver
{
    /**
     * Return a boolean showing if the logging is enabled
     *
     * @return bool
     */
    public function enabled();
}