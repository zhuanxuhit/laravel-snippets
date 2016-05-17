<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class ContainerController extends Controller {

    /**
     * @type \GreetableInterface
     */
    private $greeter;

    /**
     * ContainerController constructor.
     
     * 
*@param \GreetableInterface $greeter
     */
    public function __construct( \GreetableInterface $greeter ) {

        $this->greeter = $greeter;
    }

    public function container() {
        return $this->greeter->greet();
    }
}
