<?php namespace Talking\Mapper;

class Post {

    private $mapping = [
        'id'      => 'id',
        'title'   => 'title',
        'content' => 'content',
    ];

    public function getIdColumn()
    {

        return 'id';
    }

    public function extract( $post )
    {
        $data = [ ];
        foreach ( $this->mapping as $keyObject => $keyColumn ) {
            if ( $keyColumn != $this->getIdColumn() ) {
                $data[ $keyColumn ] = call_user_func(

                    [ $post, 'get' . ucfirst( $keyObject ) ] );
            }
        }

        return $data;
    }

    public function populate( $data, $post )
    {
        $mappingsFlipped = array_flip( $this->mapping );
        foreach ( $data as $key => $value ) {
            if ( isset( $mappingsFlipped[ $key ] ) ) {
                call_user_func_array( [ $post, 'set' . ucfirst( $mappingsFlipped[ $key ] ) ], [ $value ] );
            }

        }

        return $post;

    }
}