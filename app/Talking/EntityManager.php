<?php namespace Talking;

use Symfony\Component\HttpKernel\Event\PostResponseEvent;
use Talking\Repository\Post;
use Talking\Repository\User as UserRepository;
use Talking\Repository\Post as PostRepository;

class EntityManager {

    private $connection;
    private $userRepository;
    private $postRepository;

    /**
     * EntityManager constructor.
     *
     * @param $sqlite
     */
    public function __construct( $sqlite )
    {
        $this->connection = new \PDO( $sqlite );
        $this->connection->setAttribute( \PDO::ATTR_ERRMODE,
                                         \PDO::ERRMODE_EXCEPTION );

        $this->userRepository = null;
        $this->identityMap    = [ 'users' => [ ] ];
    }

    public function saveUser( $user )
    {
        $userMapper = new Mapper\User();
        $data       = $userMapper->extract( $user );

        $userId = call_user_func(
            [ $user, 'get' . ucfirst( $userMapper->getIdColumn() ) ] );

        if ( array_key_exists( $userId, $this->identityMap['users'] ) ) {
            // update
            $setString = '';

            foreach ( $data as $key => $value ) {
                $setString .= $key . "='$value',";
            }

            return $this->query( "UPDATE users SET " .
                                 substr( $setString, 0, -1 ) . " WHERE " . $userMapper->getIdColumn() . "=" . $userId );
        } else {
            $columnsString = implode( ", ", array_keys( $data ) );
            $valueString   = implode( "','", array_values( $data ) );

            return $this->query(
                "INSERT INTO users ($columnsString) VALUES('$valueString')" );
        }
    }

    public function query( $stmt )
    {

        return $this->connection->query( $stmt );
    }

    public function getUserRepository()
    {
        if ( !is_null( $this->userRepository ) ) {

            return $this->userRepository;
        } else {
            $this->userRepository = new UserRepository( $this );

            return $this->userRepository;
        }
    }

    public function registerUserEntity( $id, $user )
    {
        $this->identityMap['users'][ $id ] = $user;
        return $user;
    }

    public function getPostRepository()
    {
        if ( !is_null( $this->postRepository ) ) {

            return $this->postRepository;
        } else {
            $this->postRepository = new PostRepository( $this );

            return $this->postRepository;
        }
    }
}