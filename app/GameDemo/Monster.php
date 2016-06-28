<?php namespace GameDemo;


class Monster {

    private $name;
    private $hp;

    /**
     * Monster constructor.
     *
     * @param $name
     * @param $hp
     */
    public function __construct($name, $hp)
    {
        $this->name = $name;
        $this->hp = $hp;
    }

    public function notify( $loss )
    {
        if($this->hp<=0){
            echo "已死\n";
            return;
        }
        $this->hp -= $loss;
        if($this->hp<=0){
            echo "怪物 " . $this->name . " 被打死\n";
        }
        else {
            echo "怪物 " . $this->name . " 受到攻击,损失 $loss Hp\n";
        }
    }
}
