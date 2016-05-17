<?php namespace VideoRental;

class NewReleaseMovieType implements AbstractMovieType{

    /**
     * 计算价格
     *
     * @param $days
     * @return int
     */
    public function calculatePrice( $days ):int {
        return 150 + ( $days - 3 ) * 30;
    }
}