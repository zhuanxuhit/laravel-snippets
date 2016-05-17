<?php namespace VideoRental;

interface AbstractMovieType {

    /**
     * 计算价格
     *
     * @param $days
     * @return int
     */
    public function calculatePrice( $days ):int;
}