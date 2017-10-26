<?php

namespace Iivannov\Gauge\State;

use Iivannov\Gauge\Contracts\StateResolver;

class FileStateResolver implements StateResolver
{

    public function enabled()
    {
        return file_exists(storage_path('/framework/gauge'));
    }
}