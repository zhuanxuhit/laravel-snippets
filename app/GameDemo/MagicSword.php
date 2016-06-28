<?php namespace GameDemo;

class MagicSword implements IAttackStrategy {

    function attack( Monster $target )
    {
        $loss = random_int(1,10) <= 5 ? 100 : 200;
        if($loss == 200) {
            echo "出现暴击\n";
        }
        $target->notify($loss);
    }
}