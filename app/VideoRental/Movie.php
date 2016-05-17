<?php namespace VideoRental;

class Movie {

    /**
     * @type AbstractMovieType
     */
    private $type;

    /**
     * Movie constructor.
     *
     * @param string $type
     */
    public function __construct( $type )
    {
        $this->setType( $type );
    }

    public function getType():AbstractMovieType
    {
        return $this->type;
    }

    /**
     * @param $days
     * @return int
     */
    public function calculatePrice( $days )
    {
        return $this->getType()->calculatePrice($days);
    }

    /**
     * @param string $type
     */
    public function setType( string $type )
    {
        $this->type = MovieTypeFactory::create( $type );
    }

}