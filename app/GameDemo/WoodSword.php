<?php namespace GameDemo;

class WoodSword implements IAttackStrategy{

    function attack( Monster $target )
    {
        $target->notify(20);
    }
}