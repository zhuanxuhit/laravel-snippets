<?php namespace Swoole\Rate;

require __DIR__ . '/../../../vendor/autoload.php';

use Illuminate\Support\Debug\Dumper;

/**
 * 下面代码的问题是:由于每s的数据都记录了,没有过期,导致数据不断增长,有问题
 * Class Queue
 *
 * @package Swoole\Rate
 */
class Queue {

    protected $http;

    protected $lastTime;

    protected $rate = 5;

    /**
     * @type \swoole_table
     */
    protected $table;

    /**
     * @type \swoole_atomic
     */
    protected $counter;

    const TABLE_SIZE = 2048;

    /**
     * Simple constructor.
     *
     * @param $port
     */
    public function __construct( $port )
    {
        $this->http = new \swoole_http_server( '0.0.0.0', $port );
        $this->http->on( 'request', [ $this, 'onRequest' ] );
        $this->table   = new \swoole_table( self::TABLE_SIZE );
        $this->counter = new \swoole_atomic( 0 );
    }

    public function serverInfoDebug()
    {
        return json_encode(
            [
                'master_id'   => $this->http->master_pid,//返回当前服务器主进程的PID。
                'manager_pid' => $this->http->manager_pid,//返回当前服务器管理进程的PID。
                'worker_id'   => $this->http->worker_id,//得到当前Worker进程的编号，包括Task进程
                'worker_pid'  => $this->http->worker_pid,//得到当前Worker进程的操作系统进程ID。与posix_getpid()的返回值相同。
            ]
        );
    }

    public function onRequest( \swoole_http_request $request, \swoole_http_response $response )
    {
        $currentTime = microtime(true );
        $pid         = ( $this->http->worker_pid );


        // 每个请求过来后的是否判断通过,这个操作必须要单点,串行,所以也就是说必须要加速
        $this->table->lock();
        $count = $this->counter->add(1);
        $bool = true;
        $currentCount = $count + 1;
        $previousCount = $count - $this->rate;
        if($currentCount<=$this->rate){
            $this->table->set( $count, [ 'timeStamp' => $currentTime ] );
            $this->table->unlock();
        }
        else {
            $previousTime = $this->table->get( $previousCount );
            if ( $currentTime - $previousTime['timeStamp'] > 1 ) {
                $this->table->set( $currentCount, [ 'timeStamp' => $currentTime ] );
                $this->table->unlock();
            } else {
                // 去除 deny
                $bool = false;
                $this->counter->sub( 1 );
                $this->table->unlock();
            }
        }


        if ( !$bool ) {
            $response->header( "Content-Type", "text/html; charset=utf-8" );
            $response->end( "<h1>Access deny. #" . rand( 1000, 9999 ) . "</h1>" );
            echo "deny worker_pid:$pid $currentTime:$currentCount\n";
        } else {
            $response->header( "Content-Type", "text/html; charset=utf-8" );
            $response->end( $this->serverInfoDebug() );
            echo "accept worker_pid: $pid $currentTime:$currentCount\n";
        }
    }

    public function start()
    {
        $this->table->column( 'timeStamp', \swoole_table::TYPE_FLOAT );
        $this->table->create();
        $this->http->set( [ 'worker_num' => 2 ] );
        //        $this->table->set( 'lastTime', ['lastTime'=>0]);
        $this->http->start();
    }
}

$simple = new Queue( 9090 );
$simple->start();