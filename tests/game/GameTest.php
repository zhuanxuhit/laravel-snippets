<?php

use GameDemo\IronSword;
use GameDemo\MagicSword;
use GameDemo\Monster;
use GameDemo\Role;
use GameDemo\WoodSword;
use Illuminate\Container\Container;

class GameTest extends PHPUnit_Framework_TestCase {

    /**
     *
     */
    public function test_use_sword()
    {
        //生成怪物
        $monster1 = new Monster( "小怪A", 50 );
        $monster2 = new Monster( "小怪B", 50 );
        $monster3 = new Monster( "关主", 200 );
        $monster4 = new Monster( "最终Boss", 1000 );

        //生成角色
        $role = new Role("超级英雄");

        //木剑攻击
        $role->setWeapon(new WoodSword());
        $role->attack($monster1);
        //铁剑攻击
        $role->setWeapon(new IronSword());
        $role->attack($monster2);
        $role->attack($monster3);

        //魔剑攻击
        $role->setWeapon(new MagicSword());
        $role->attack($monster3);
        $role->attack($monster4);
        $role->attack($monster4);
        $role->attack($monster4);
    }

    public function test_user_container()
    {
        $container = new Container();
        $container->bind('GameDemo\IAttackStrategy','GameDemo\WoodSword');

        //生成怪物
        $monster1 = new Monster( "小怪A", 50 );
        $monster2 = new Monster( "小怪B", 50 );
        $monster3 = new Monster( "关主", 200 );
        $monster4 = new Monster( "最终Boss", 1000 );

        //生成角色
        /**
         * @var Role
         */
        $role = $container->make('GameDemo\Role',['英雄']);
        $container->rebinding('GameDemo\IAttackStrategy',function($c, $weapon) use($role){
            $role->setWeapon($weapon);
        });
        //木剑攻击
        $role->attack($monster1);
        //铁剑攻击
        $container->bind('GameDemo\IAttackStrategy','GameDemo\IronSword');
        $role->attack($monster2);
        $role->attack($monster3);

        //魔剑攻击
        $container->bind('GameDemo\IAttackStrategy','GameDemo\MagicSword');
        $role->attack($monster3);
        $role->attack($monster4);
        $role->attack($monster4);
        $role->attack($monster4);
    }
}