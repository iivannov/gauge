<?php


namespace Iivannov\Gauge\Drivers;

use Iivannov\Gauge\Contracts\LogDriver;
use Iivannov\Gauge\Query;

class RuntimeDriver implements LogDriver
{
    public function single(Query $query)
    {
        echo "QUERY: " . $query->getRawQuery() . " IN " . $query->getTime() . " ms \r\n";
    }
}