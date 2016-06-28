<?php //namespace container;

use Illuminate\Container\Container;

class LaravelContainerTest extends \PHPUnit_Framework_TestCase {

    public function testClosureResolution()
    {
        $name = 'zhuanxu';
        $container = new Container();

        $container->bind('name',function($c) use ($name){
            return $name;
        });

        $this->assertEquals($name,$container->make('name'));
//        dd($container);
        $this->assertInstanceOf('ContainerConcreteStub',$container->make('ContainerConcreteStub'));
    }

    public function testSimpleInstance()
    {
        $c = new Container();
        $name = 'zhuanxu';
        $c->instance('name',$name);
        $this->assertEquals($name,$c->make('name'));
    }
}

class ContainerConcreteStub
{
}