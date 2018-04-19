<?php

namespace Iivannov\Gauge;


class QueryCollection implements \IteratorAggregate
{
    protected $items = [];

    public function __construct($items = [])
    {
        foreach ($items as $item) {
            if ($item instanceof Query) {
                $this->items[] = $item;
            }
        }
    }

    public function push(Query $query)
    {
        $this->items[] = $query;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->items);
    }

    public function toJson()
    {
        return json_encode($this->items);
    }

    public function toArray()
    {
        return $this->items;
    }


}