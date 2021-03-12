<?php
namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApplicationAvailabilityFunctionalTest extends WebTestCase
{
    public function testShowPost()
    {
        $client = static::createClient();

        $client->request('GET', '/figure/add');
        $client->catchExceptions(false);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}