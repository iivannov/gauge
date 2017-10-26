<?php


namespace Iivannov\Gauge\Drivers;

use Iivannov\Gauge\Contracts\LogDriver;
use Iivannov\Gauge\Query;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;

class HttpDriver implements LogDriver
{
    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * @var \GuzzleHttp\ClientInterface
     */
    protected $httpClient;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var string
     */
    protected $token;


    public function __construct(\Illuminate\Http\Request $request, ClientInterface $httpClient, string $url, string $token)
    {
        $this->request = $request;
        $this->httpClient = $httpClient;

        $this->url = $url;
        $this->token = $token;
    }

    public function single(Query $query)
    {
        $json = [
            'statement' => $query->getStatement(),
            'bindings' => $query->getBindings(),
            'time' => $query->getTime()
        ];

        try {
            $this->httpClient->request('post', $this->url . '/query', ['json' => $json]);
        } catch (ConnectException $e) {
            //
        } catch (ClientException $e) {
            //
        }
    }

    public function bulk(Query $query)
    {
        $json = [
            'statement' => $query->getStatement(),
            'bindings' => $query->getBindings(),
            'time' => $query->getTime()
        ];

        try {
            $this->httpClient->request('post', $this->url . '/query', ['json' => $json]);
        } catch (ConnectException $e) {
            //
        } catch (ClientException $e) {
            //
        }
    }

}