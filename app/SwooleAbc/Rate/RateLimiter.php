<?php namespace SwooleAbc\Rate;


require __DIR__.'/../../../vendor/autoload.php';

use Illuminate\Support\Debug\Dumper;

class RateLimiter {

    protected $http;

    protected $lastTime;

    protected $rate = 5;

    protected $lock;

    protected $tickets;

    /**
     * Simple constructor.
     *
     * @param $port
     */
    public function __construct($port)
    {
        $this->http = new \swoole_http_server('0.0.0.0',$port);
        $this->http->on('request',array($this,'onRequest'));
        $this->http->on('WorkerStart',array($this,'onWorkerStart'));
        $this->lock = new \swoole_lock( SWOOLE_MUTEX );
        $this->tickets = new \swoole_atomic( 0 );
    }

    public function onWorkerStart(\swoole_server $server, $worker_id)
    {
        if($worker_id ==0){
            $server->tick( 1000/$this->rate, [$this,'addTicket'] );
        }
    }

    public function serverInfoDebug()
    {
        return json_encode(
            [
                'master_id' => $this->http->master_pid,//返回当前服务器主进程的PID。
                'manager_pid' => $this->http->manager_pid,//返回当前服务器管理进程的PID。
                'worker_id' => $this->http->worker_id,//得到当前Worker进程的编号，包括Task进程
                'worker_pid' => $this->http->worker_pid,//得到当前Worker进程的操作系统进程ID。与posix_getpid()的返回值相同。
            ]
        );
    }

    public function onRequest( \swoole_http_request $request, \swoole_http_response $response )
    {
        $currentTime = microtime(true );
        $pid         = ( $this->http->worker_pid );

        $bool = $this->getTicket();

        if ( !$bool ) {
            $response->header( "Content-Type", "text/html; charset=utf-8" );
            $response->end( "<h1>Access deny. #" . rand( 1000, 9999 ) . "</h1>" );
            echo "deny worker_pid:$pid $currentTime\n";
        } else {
            $response->header( "Content-Type", "text/html; charset=utf-8" );
            $response->end( $this->serverInfoDebug() );
            echo "accept worker_pid: $pid $currentTime\n";
        }
    }

    public function getTicket()
    {
        $this->lock->lock();
        $count = $this->tickets->get();
//        echo $count . PHP_EOL;
        $bool = false;
        if($count>0){
            $bool = true;
            $this->tickets->sub( 1 );
        }
        $this->lock->unlock();
        return $bool;
    }

    public function addTicket()
    {
        $this->lock->lock();
        $count = $this->tickets->get();
//        echo $count . PHP_EOL;
        if($count<$this->rate){
            $this->tickets->add( 1 );
        }
        $this->lock->unlock();
    }

    public function start()
    {
        $this->http->set(array('worker_num' => 2));
        $this->http->start();
    }
}

$simple = new RateLimiter(9090);
$simple->start();