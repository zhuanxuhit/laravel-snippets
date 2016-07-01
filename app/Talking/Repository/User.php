<?php namespace Talking\Repository;

use Talking\Entity\User as UserEntity;
use Talking\EntityManager;
use Talking\Mapper\User as UserMapper;

class User {

    /**
     * @type EntityManager
     */
    private $em;
    private $mapper;

    /**
     * User constructor.
     *
     * @param $em
     */
    public function __construct( $em )
    {
        $this->mapper = new UserMapper();
        $this->em     = $em;
    }

    public function findOneById( $id )
    {
        $userData = $this->em->query( 'SELECT * FROM users WHERE id = ' . $id )->fetch();
        $newUser  = new UserEntity();

        $newUser->setPostRepository( $this->em->getPostRepository() );

        return $this->em->registerUserEntity(
            $id,
            $this->mapper->populate( $userData, new UserEntity() ) );
    }
}