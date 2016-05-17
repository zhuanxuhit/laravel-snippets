<?php namespace VideoRental;

use Illuminate\Support\Facades\App;

class MovieTypeFactory {

    /**
     * 工厂类
     * @param string $type
     * @return AbstractMovieType
     */
    public static function create( string $type ) : AbstractMovieType
    {
        $app = App::getFacadeApplication();
        $app->bind(AbstractMovieType::class,'VideoRental\\' . $type . 'MovieType');
        return $app->make(AbstractMovieType::class);
    }
}