<?php


namespace Iivannov\Gauge\Contracts;


use Iivannov\Gauge\QueryCollection;

interface QueryLogger
{
    /**
     * Return a boolean showing if the query logger should run
     *
     * @return bool
     */
    public function shouldRun();

    /**
     * @param QueryCollection $query
     * @return void
     */
    public function handle(QueryCollection $query);
}