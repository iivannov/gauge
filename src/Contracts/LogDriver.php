<?php

namespace Iivannov\Gauge\Contracts;

use Iivannov\Gauge\Query;
use Iivannov\Gauge\QueryCollection;

interface LogDriver
{
    public function single(Query $query);

    public function bulk(QueryCollection $collection);
}