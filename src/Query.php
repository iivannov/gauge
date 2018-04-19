<?php

namespace Iivannov\Gauge;

class Query implements \JsonSerializable
{
    protected $time;

    protected $bindings;

    protected $statement;

    public function __construct($attributes)
    {
        if (!isset($attributes['query'], $attributes['time'], $attributes['bindings'])) {
            throw new \InvalidArgumentException('Unable to construct Query from given data');
        }

        $this->time = $attributes['time'];
        $this->bindings = $attributes['bindings'];
        $this->statement = $attributes['query'];
    }

    /**
     * Return time in milliseconds
     * @return float
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * An array of binding values
     * @return array
     */
    public function getBindings()
    {
        return $this->bindings;
    }


    /**
     * Return the prepared SQL statement
     * @return string
     */
    public function getStatement()
    {
        return $this->statement;
    }

    /**
     * Return an unique fingerprint for the current sql statement
     * @return string
     */
    public function getFingerprint()
    {
        return md5($this->statement);
    }

    /**
     * Return the raw SQL query with values
     * @return string
     */
    public function getRawQuery()
    {
        return vsprintf(str_replace('?', '%s', $this->statement), $this->bindings);
    }


    function jsonSerialize()
    {
        return [
            'statement' => $this->statement,
            'bindings' => $this->bindings,
            'time' => $this->time,
        ];
    }
}