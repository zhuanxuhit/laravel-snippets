<?php namespace Swoole\Rate;

class Simple {

    protected $http;

    protected $lastTime;

    protected $rate = 0.1;
    /**
     * Simple constructor.
     *
     * @param $port
     */
    public function __construct($port)
    {
        $this->http = new \swoole_http_server('0.0.0.0',$port);
        $this->http->on('request',array($this,'onRequest'));
    }

    public function onRequest( \swoole_http_request $request, \swoole_http_response $response )
    {
        $lastTime = $this->lastTime;
        $currentTime = microtime(true);

        if(($currentTime-$lastTime)<1/$this->rate){
            $response->header("Content-Type", "text/html; charset=utf-8");
            $response->end("<h1>Access deny. #".rand(1000, 9999)."</h1>");
        }
        else {
            $this->lastTime = $currentTime;
            $response->header("Content-Type", "text/html; charset=utf-8");
            $response->end("<h1>Hello Swoole. #".rand(1000, 9999)."</h1>");
        }
    }

    public function start()
    {
        $this->http->start();
    }
}

$simple = new Simple(9090);
$simple->start();