<?php namespace GameDemo;

interface IAttackStrategy {
    function attack(Monster $target);
}