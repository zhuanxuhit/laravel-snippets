<?php namespace GameDemo;

class Role {

    /**
     * @type IAttackStrategy
     */
    protected $weapon;
    private   $name;

    /**
     * Role constructor.
     *
     * @param                 $name
     * @param IAttackStrategy $weapon
     */
    public function __construct($name, IAttackStrategy $weapon = null)
    {
        $this->name = $name;
        $this->weapon = $weapon;
    }

    public function setWeapon( IAttackStrategy $weapon )
    {
        $this->weapon = $weapon;
    }

    /**
     * @return IAttackStrategy
     */
    public function getWeapon()
    {
        return $this->weapon;
    }

    function attack( Monster $target )
    {
        $this->weapon->attack($target);
    }
}