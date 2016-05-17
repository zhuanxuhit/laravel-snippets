<?php namespace VideoRental;

class Customer {

    /**
     * @type array Order
     */
    protected $orders = [ ];

    /**
     * Customer constructor.
     */
    public function __construct() {
    }

    /**
     * 新增订单
     *
     * @param Order $order
     */
    public function addOrder( Order $order ) {
        $this->orders[] = $order;
    }

    /**
     * 计算订单金额
     *
     * @return int
     */
    public function calculateTotalPrice() {
        $totalPrice = 0;

        /** @type Order $order */
        foreach ( $this->orders as $order ) {
            $totalPrice += $order->calculatePrice();
        }

        return $totalPrice;
    }

}