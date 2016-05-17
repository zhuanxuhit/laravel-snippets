<?php namespace VideoRental;

class RegularMovieType implements AbstractMovieType{

    /**
     * 计算价格
     *
     * @param $days
     * @return int
     */
    public function calculatePrice( $days ):int {
        return 100 + ( $days - 7 ) * 10;
    }
}