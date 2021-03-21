<?php

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    function testValuesAfterCreation(){

        $user = (new User())->setPseudo('userTest')
                ->setPassword((password_hash('userTest', PASSWORD_BCRYPT )))
                ->setMailAddress('userTest@snowtricks.fr')
                ->setCreationDate(new \DateTime());

        $this->assertEquals(false, $user->isVerified());
        $this->assertEquals('userTest', $user->getPseudo());
        $this->assertEquals($user->getPseudo(), $user->getUsername());
        $this->assertEquals('userTest@snowtricks.fr', $user->getMailAddress());
        $this->assertEquals(true, password_verify('userTest', $user->getPassword()));
        $this->assertNotNull($user->getCreationDate());
    }
}