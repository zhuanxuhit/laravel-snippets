<?php namespace container;

class OrderMysqlRepository implements Repository{
    protected $mysql;

    public function save()
    {
        // TODO: Implement save() method.
    }

    public function select()
    {
        // TODO: Implement select() method.
    }
}
class OrderRedisRepository implements  Repository{

    public function save()
    {
        // TODO: Implement save() method.
    }

    public function select()
    {
        // TODO: Implement select() method.
    }
}
interface Repository {

    public function save();
    public function select();
}

class Order {

    /**
     * @type Repository
     */
    private $repository;

    /**
     * Order constructor.
     *
     * @param Repository $repository
     */
    public function __construct(Repository $repository)
    {
//        $this->repository = new OrderMysqlRepository();
//        $this->repository = new OrderRedisRepository();

        $this->repository = $repository;
    }
}

class Container
{
    protected $binds;

    protected $instances;

    public function bind($abstract, $concrete)
    {
        if ($concrete instanceof \Closure) {
            $this->binds[$abstract] = $concrete;
        } else {
            $this->instances[$abstract] = $concrete;
        }
    }

    public function make($abstract, $parameters = [])
    {
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }

        array_unshift($parameters, $this);

        return call_user_func_array($this->binds[$abstract], $parameters);
    }
}

class SuperManTest extends \PHPUnit_Framework_TestCase {

    public function testContainer()
    {
        $container = new Container();
        $container->bind('order',function(Container $c,$repository){
            return new Order($c->make($repository));
        });
        $container->bind('Repository',new OrderRedisRepository);
        $order = $container->make('order',['Repository']);
    }
}