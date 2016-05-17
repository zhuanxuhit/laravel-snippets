<?php namespace VideoRental;

class Order {

    /**
     * @type \VideoRental\Movie
     */
    private $movie;
    /**
     * @type int
     */
    private $days;

    /**
     * Order constructor.
     *
     * @param \VideoRental\Movie $movie
     * @param int                $days
     */
    public function __construct( $movie, $days ) {

        $this->movie = $movie;
        $this->days  = $days;
    }

    /**
     * @return \VideoRental\Movie
     */
    public function getMovie() : Movie{
        return $this->movie;
    }

    /**
     * @return int
     */
    public function getDays() : int{
        return $this->days;
    }

    /**
     * @return int
     */
    public function calculatePrice() {
        return $this->movie->calculatePrice($this->getDays());
    }

}