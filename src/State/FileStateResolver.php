<?php

namespace Iivannov\Gauge\State;

use Iivannov\Gauge\Contracts\StateResolver;

class FileStateResolver implements StateResolver
{

    protected $path;

    protected $filename;

    public function __construct($path, $filename)
    {
        $this->path = $path;
        $this->filename = $filename;
    }

    public function enabled()
    {
        return file_exists($this->path . DIRECTORY_SEPARATOR . $this->filename);
    }
}