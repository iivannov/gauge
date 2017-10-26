<?php


namespace Iivannov\Gauge\Drivers;

use Iivannov\Gauge\Contracts\LogDriver;
use Iivannov\Gauge\Query;

class JsonFileDriver implements LogDriver
{
    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * @var \Illuminate\Contracts\Filesystem\Filesystem
     */
    protected $filesystem;

    /**
     * @var string
     */
    protected $filepath;

    public function __construct(\Illuminate\Http\Request $request, \Illuminate\Contracts\Filesystem\Filesystem $filesystem)
    {
        $this->request = $request;
        $this->filesystem = $filesystem;

        $this->filepath = '/gauge/json.' . gmdate('Ymd');
    }

    public function single(Query $query)
    {
        $this->filesystem->append($this->getFilePath(), $this->getLine($query));
    }

    private function getFilePath()
    {
        return $this->filepath;
    }

    private function getLine(Query $query)
    {
        $requestUid = md5($this->request->fingerprint() . LARAVEL_START);

        return json_encode([
           'request' => $requestUid,
           'fingerprint' => $query->getFingerprint(),
           'statement' => $query->getStatement(),
            'bindings' => $query->getBindings(),
            'time' => $query->getTime(),
        ]);
    }

}