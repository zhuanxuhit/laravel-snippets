<?php

use VideoRental\Customer;
use VideoRental\Movie;
use VideoRental\Order;

class CustomerTest extends TestCase
{

    /**
     * @group CustomerTest
     */
    public function test_order_1_regular_movie_with_10_days() {
        /** arrange */
        $movie = new Movie('Regular');
        $order = new Order($movie,10);
        $target = new Customer();
        $target->addOrder($order);

        $expected = 130;
        /** act */
        $actual = $target->calculateTotalPrice();

        /** asset */
        $this->assertEquals($expected, $actual);

    }

    /**
     * @group CustomerTest
     */
    public function test_order_1_new_release_movie_with_5_days() {
        /** arrange */
        $movie = new Movie('NewRelease');
        $order = new Order($movie,5);
        $target = new Customer();
        $target->addOrder($order);

        $expected = 210;
        /** act */
        $actual = $target->calculateTotalPrice();

        /** asset */
        $this->assertEquals($expected, $actual);

    }

    /**
     * @group CustomerTest
     */
    public function test_order_1_children_movie_with_8_days() {
        /** arrange */
        $movie = new Movie('Children');
        $order = new Order($movie,8);
        $target = new Customer();
        $target->addOrder($order);

        $expected = 50;
        /** act */
        $actual = $target->calculateTotalPrice();

        /** asset */
        $this->assertEquals($expected, $actual);

    }
}
