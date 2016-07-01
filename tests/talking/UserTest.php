<?php

namespace talking;

use Talking\Entity\User;
use Talking\EntityManager;

class UserTest extends \PHPUnit_Framework_TestCase {

    public function test_assemble_user_name()
    {
        $user = new User();
        $user->setFirstName( 'Max' );
        $user->setLastName( 'Mustermann' );
        $user->setGender( 0 );
        $user->setNamePrefix( 'Prof. Dr' );
        $this->assertEquals( "Mr. Prof. Dr Max Mustermann", $user->assembleDisplayName() );
    }

    public function test_mapper_from_database_to_object()
    {
        $dsn = 'sqlite:' . __DIR__ . '/../../app/Talking/talking.db';
        $db  = new \PDO( $dsn );
        $db->setAttribute( \PDO::ATTR_ERRMODE,
                           \PDO::ERRMODE_EXCEPTION );
        //        $db->exec("INSERT INTO users (first_name, last_name, gender, name_prefix) VALUES('Max', 'Mustermann', '0', 'Prof. Dr.')");
        //        $userData = $db->exec('SELECT * FROM users WHERE id = 1');
        $userData = $db->query( 'SELECT * FROM users WHERE id=1' )->fetch();//->fetch();

        $mapper = new \Talking\Mapper\User();
        $user   = new User();
        $mapper->populate( $userData, $user );
        $this->assertEquals( "Mr. Prof. Dr. Max Mustermann", $user->assembleDisplayName() );
    }

    public function test_use_entity_manager()
    {
        $dsn  = 'sqlite:' . __DIR__ . '/../../app/Talking/talking.db';
        $em   = new EntityManager( $dsn );
        $user = $em->getUserRepository()->findOneById( 1 );
        $this->assertEquals( "Mr. Prof. Dr. Max Mustermann", $user->assembleDisplayName() );
    }

    public function test_save_a_new_user()
    {
        $dsn  = 'sqlite:' . __DIR__ . '/../../app/Talking/talking.db';
        $em   = new EntityManager( $dsn );
        $newUser = new User();
        $newUser->setFirstName( 'Ute' );
        $newUser->setLastName( 'Musermann' );
        $newUser->setGender( 1 );
        $em->saveUser( $newUser );
//        dd($newUser->assembleDisplayName());
        $this->assertEquals( "Mrs. Ute Musermann", $newUser->assembleDisplayName() );

    }
}
