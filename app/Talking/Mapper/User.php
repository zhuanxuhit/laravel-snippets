<?php namespace Talking\Mapper;

class User {

    private $mapping = [
        'id'         => 'id',
        'firstName'  => 'first_name',
        'lastName'   => 'last_name',
        'gender'     => 'gender',
        'namePrefix' => 'name_prefix',
    ];

    public function extract( $user )
    {
        $data = [ ];

        foreach ( $this->mapping as $keyObject => $keyColumn ) {
            if ( $keyColumn != 'id' ) {
                $data[ $keyColumn ] = call_user_func(

                    [ $user, 'get' . ucfirst( $keyObject ) ] );
            }

        }

        return $data;
    }

    public function populate( $data, $user )
    {
        $mappingsFlipped = array_flip( $this->mapping );

        foreach ( $data as $key => $value ) {
            if ( isset( $mappingsFlipped[ $key ] ) ) {
                call_user_func_array(
                    [ $user, 'set' . ucfirst( $mappingsFlipped[ $key ] ) ],
                    [ $value ]
                );
            }
        }

        return $user;

    }

    public function getIdColumn()
    {
        return 'id';
    }
}