<?php

namespace Iivannov\Gauge;


use Iivannov\Gauge\Contracts\LogDriver;
use Iivannov\Gauge\Contracts\StateResolver;

class QueryLogger implements \Iivannov\Gauge\Contracts\QueryLogger
{

    /**
     * @var StateResolver
     */
    private $state;

    /**
     * @var LogDriver
     */
    private $driver;


    public function __construct(StateResolver $state, LogDriver $driver)
    {
        $this->driver = $driver;
        $this->state = $state;
    }

    public function shouldRun()
    {
        return $this->state->enabled();
    }

    public function handle($queries)
    {
        foreach ($queries as $query) {
            if (!$query instanceof Query) {
                continue;
            }

            $this->driver->single($query);
        }
    }


}