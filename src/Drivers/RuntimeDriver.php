<?php


namespace Iivannov\Gauge\Drivers;

use Iivannov\Gauge\Contracts\LogDriver;
use Iivannov\Gauge\Query;
use Iivannov\Gauge\QueryCollection;

class RuntimeDriver implements LogDriver
{
    public function single(Query $query)
    {
        echo $this->getLine($query);
    }

    public function bulk(QueryCollection $collection)
    {
        foreach ($collection as $query) {
            echo $this->getLine($query);
        }
    }

    private function getLine(Query $query)
    {
        return "QUERY: " . $query->getRawQuery() . " IN " . $query->getTime() . " ms \r\n";
    }
}