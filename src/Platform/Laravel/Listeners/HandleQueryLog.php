<?php


namespace Iivannov\Gauge\Platform\Laravel\Listeners;

use Iivannov\Gauge\Contracts\QueryLogger;
use Iivannov\Gauge\Query;
use Iivannov\Gauge\QueryCollection;

class HandleQueryLog
{
    /**
     * @var \Illuminate\Database\Connection
     */
    protected $db;

    /**
     * @var QueryLogger
     */
    protected $handler;


    public function __construct(\Illuminate\Database\Connection $db, \Iivannov\Gauge\QueryLogger $handler)
    {
        $this->db = $db;
        $this->handler = $handler;
    }

    public function handle(\Illuminate\Foundation\Http\Events\RequestHandled $event)
    {
        if(!$this->handler->shouldRun()) {
            return;
        }

        $collection = new QueryCollection();
        foreach ($this->db->getQueryLog() as $query) {
            $collection->push(new Query($query));
        }

        $this->handler->handle($collection);
    }
}