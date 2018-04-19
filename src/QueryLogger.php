<?php

namespace Iivannov\Gauge;


use Iivannov\Gauge\Contracts\LogDriver;
use Iivannov\Gauge\Contracts\QueryLogger as QueryLoggerContract;
use Iivannov\Gauge\Contracts\StateResolver;

class QueryLogger implements QueryLoggerContract
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

    public function handle(QueryCollection $collection)
    {
        $this->driver->bulk($collection);
    }


}