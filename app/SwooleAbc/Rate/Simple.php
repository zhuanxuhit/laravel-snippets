<?php namespace Swoole\Rate;

require __DIR__.'/../../../vendor/autoload.php';

use Illuminate\Support\Debug\Dumper;

/**
 * 下面代码的问题是:由于是多个进程,所以子进程继承了父进程的Simple
 * Class Simple
 *
 * @package Swoole\Rate
 */
class Simple {

    protected $http;

    protected $lastTime;

    protected $rate = 5;

    /**
     * @type \swoole_table
     */
    protected $table;

    /**
     * Simple constructor.
     *
     * @param $port
     */
    public function __construct($port)
    {
        $this->http = new \swoole_http_server('0.0.0.0',$port);
        $this->http->on('request',array($this,'onRequest'));
        $this->table = new \swoole_table(1);
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
        $currentTime = microtime(true);
        $pid =($this->http->worker_pid);
        $this->table->lock();
        $lastTime = $this->table->get( 'lastTime' );
        $lastTime = $lastTime['lastTime'];
        if(($currentTime-$lastTime)<1/$this->rate){
            $this->table->unlock();
            $response->header("Content-Type", "text/html; charset=utf-8");
            $response->end("<h1>Access deny. #".rand(1000, 9999)."</h1>");
            echo "deny worker_pid: $pid lastTime:$lastTime currentTime:$currentTime\n";
        }
        else {
            $this->table->set( 'lastTime', [ 'lastTime' => $currentTime] );
            $this->table->unlock();

            $response->header("Content-Type", "text/html; charset=utf-8");
            $response->end($this->serverInfoDebug());
            echo "accept worker_pid: $pid lastTime:$lastTime currentTime:$currentTime\n";
        }
    }

    public function start()
    {
        $this->table->column( 'lastTime', \swoole_table::TYPE_FLOAT );
        $this->table->create();
        $this->http->set(array('worker_num' => 2));
        $this->table->set( 'lastTime', ['lastTime'=>0]);
        $this->http->start();
    }
}

$simple = new Simple(9090);
$simple->start();