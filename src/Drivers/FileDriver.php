<?php


namespace Iivannov\Gauge\Drivers;

use Iivannov\Gauge\Contracts\LogDriver;
use Iivannov\Gauge\Query;
use Iivannov\Gauge\QueryCollection;

class FileDriver implements LogDriver
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

        $this->filepath = '/gauge/log.' . gmdate('Ymd');
    }

    public function single(Query $query)
    {
        $this->filesystem->append($this->getFilePath(), $this->getLine($query));
    }

    public function bulk(QueryCollection $collection)
    {
        $input = '';
        foreach ($collection as $query) {
            $input .= $this->getLine($query) . PHP_EOL;
        }

        $this->filesystem->append($this->getFilePath(), $input);
    }

    private function getFilePath()
    {
        return $this->filepath;
    }

    private function getLine(Query $query)
    {
        $requestUid = md5($this->request->fingerprint() . LARAVEL_START);

        return "REQUEST: " . $requestUid . " FINGERPRINT: " . $query->getFingerprint() . " QUERY: " . $query->getRawQuery() . " IN " . $query->getTime() . " ms";
    }


}