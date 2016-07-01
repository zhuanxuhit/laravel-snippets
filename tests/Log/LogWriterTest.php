<?php

use Monolog\Logger;

class LogWriterTest extends PHPUnit_Framework_TestCase {

    public function test_monolog()
    {
        $writer = new \Illuminate\Log\Writer(new Logger('test'));
        $writer->useFiles('./log','debug');
        $writer->debug("hello");
    }
}