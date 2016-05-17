<?php namespace App\SwooleAbc\Rate;

class Bucket {

    protected $http;

    protected $timeStamp=[];

    protected $rate = 5;
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

        $currentTime = time();


        $count = intval($this->timeStamp[$currentTime]);
        if( ($count+1)>$this->rate){// deny
            $response->header("Content-Type", "text/html; charset=utf-8");
            $response->end("<h1>Access deny. #".rand(1000, 9999)."</h1>");
        }
        else {
            $response->header("Content-Type", "text/html; charset=utf-8");
            $response->end("<h1>Hello Swoole. #".rand(1000, 9999)."</h1>");
        }
    }

    public function start()
    {
        $this->http->start();
    }
}
