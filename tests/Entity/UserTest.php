<?php

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testNewUserIsntVerified()
    {
        $product = new User();
        $product->setIsVerified(0);
        $this->assertSame(false, $product->isVerified());

    }
}