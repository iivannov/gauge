<?php

namespace Iivannov\Gauge\Contracts;

use Iivannov\Gauge\Query;

interface LogDriver
{
    public function single(Query $query);
}