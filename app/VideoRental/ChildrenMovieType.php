<?php namespace VideoRental;

class ChildrenMovieType implements AbstractMovieType{

    /**
     * 计算价格
     *
     * @param $days
     * @return int
     */
    public function calculatePrice( $days ):int {
        return 40 + ( $days - 7 ) * 10;
    }
}