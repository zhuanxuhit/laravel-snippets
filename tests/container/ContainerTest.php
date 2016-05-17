<?php namespace container;

use Illuminate\Container\Container;


interface Foo {
    public function foo();
}
class Bar {

    /**
     * Bar constructor.
     *
     * @param int $param1
     * @param     $param2
     * @param Foo $foo
     */
    public function __construct( $param1=1, $param2, Foo $foo )
    {
    }
}

class ContainerTest extends \PHPUnit_Framework_TestCase {

    public function test_simple_instance()
    {
        /** arrange */
        $c        = new Container();
        $expected = "hello";
        /** act */
        $c->instance( 'name', 'hello' );
        $actual = $c->make( 'name' );
        /** asset */
        $this->assertEquals( $expected, $actual );
    }

    public function test_simple_bind()
    {
        /** arrange */
        $c        = new Container();
        $expected = "hello world";
        /** act */
        $c->bind( 'name', function ( $c, $name ) {
            return "hello " . array_first( $name );
        } );
        $actual = $c->make( 'name', [ 'world' ] );
        /** asset */
        $this->assertEquals( $expected, $actual );
    }

    public function test_get_default_value()
    {
        
    }

    public function test_reflection()
    {
        $concrete  = 'container\Bar';
        $reflector = new \ReflectionClass( $concrete );
        $this->assertTrue( $reflector->isInstantiable() );
        $constructor = $reflector->getConstructor();
        //        dd($constructor);
        $dependencies = $constructor->getParameters();
        dd($dependencies[2]->getClass());
        
//        dd($dependencies[0]->isDefaultValueAvailable());
        
        $c            = new Container();
        $c->singleton( 'Bar', 'container\Bar' );
        //        $c->make('Bar',[1,2]);
        $c->make( 'Bar', [ 'param1' => 1, 'param2' => 2 ] );
    }
}
