<?php


namespace Iivannov\Gauge\Contracts;


interface QueryLogger
{
    public function shouldRun();

    public function handle($query);
}