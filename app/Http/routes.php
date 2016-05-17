<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
//dd(debug_backtrace());
Route::get('/', function () {
//    return debug_backtrace();
    return view('welcome');
});

Route::get('/container', function(){
    // Get Application instance
    $app = App::getFacadeRoot();
    $app['some_array'] = array('foo' => 'bar');
});

Route::get('/closure',function(){
    // Get Application instance
    $app = App::getFacadeRoot();
    $app['closure'] = function(){
        return "hello Laravel!";
    };
    return $app['closure'];
});

//interface GreetableInterface {
//    public function greet();
//};
//class HelloWorld implements GreetableInterface {
//
//    public function greet() {
//        return "Hello, World!";
//    }
//};

//App::bind('GreetableInterface','HelloWorld');
//
//Route::get('controller',"ContainerController@container");

//Route::get('/ioc',function(){
//    // Get Application instance
//    $app = App::getFacadeRoot();
//    $app->bind('GreetableInterface',function(){
//        return new HelloWorld;
//    });
//
//    $greeter = $app->make('GreetableInterface');
//
//    return $greeter->greet();
//
//});
