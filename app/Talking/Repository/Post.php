<?php namespace Talking\Repository;

use Talking\EntityManager;
use Talking\Mapper\Post as PostMapper;
use Talking\Entity\User as UserEntity;
use Talking\Entity\Post as PostEntity;

class Post {

    /**
     * @type EntityManager
     */
    private $em;
    //    private $mapper;

    /**
     * User constructor.
     *
     * @param $em
     */
    public function __construct( $em )
    {
        $this->mapper = new PostMapper();
        $this->em     = $em;
    }

    public function findByUser( UserEntity $user )
    {
        $postsData = $this->em->query( 'SELECT * FROM posts WHERE user_id = ' . $user->getId() )->fetchAll();

        $posts = [ ];

        foreach ( $postsData as $postData ) {
            $newPost = new PostEntity();
            $posts[] = $this->mapper->populate( $postData, $newPost );
        }

        return $posts;

    }
}