<?php namespace pipeline;

/**
 * foldl :: (b -> a -> b) -> b -> t a -> b
 * @param       $f
 * @param       $zero
 * @param array $list
 * @return mixed
 */
function foldLeft($f, $zero, array $list) {
    if(empty($list)){
        return $zero;
    }
    else {
        $head = array_first($list);
        $tails = array_slice($list,1);
        return foldLeft($f,$f($zero,$head),$tails);
    }
}


class PipelineTest extends \PHPUnit_Framework_TestCase{

    public function testFoldLeft()
    {
        $arr = range(1,10);
        $sum = foldLeft(function($a,$b){
            return $a + $b;
        },0,$arr);
        $this->assertEquals(55,$sum);
    }

    public function testFoldLeftFunc()
    {
        $arr = [
            function($a, $stack){
                return $stack($a . " 1 ");
            },
            function($a, $stack){
                return $stack($a . " 2 ");
            },
            function($a, $stack){
                return $stack($a . " 3 ");
            },
        ];
        //foldl :: (b -> a -> b) -> b -> t a -> b
        $result = foldLeft(function($stack,$item){
            return function($pass) use ($stack, $item){
                return call_user_func($item,$pass,$stack);
            };
        },function( $pass ){
            return array("zero"=>$pass);
        },array_reverse($arr));
        $res = call_user_func($result,"finial value");
        $this->assertEquals(["zero" => "finial value 1  2  3 "],$res);
    }
}

