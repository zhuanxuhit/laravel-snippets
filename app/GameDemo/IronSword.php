<?php namespace GameDemo;

class IronSword implements IAttackStrategy{

    function attack( Monster $target )
    {
        $target->notify(50);
    }
}