<?php
namespace App\Tests;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApplicationAvailabilityFunctionalTest extends WebTestCase
{
    /**
     * @dataProvider provideVisitorUrls
     */
    public function testVisitorPagesAreSuccessful($url)
    {
        $client = self::createClient();
        $client->request('GET', $url);

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function provideVisitorUrls()
    {
        return [
            ['/'],
            ['/login'],
            ['/register'],
        ];
    }

    /**
     * @dataProvider provideUserUrls
     */
    public function testUserPagesAreSuccessful($url)
    {
        $client = self::createClient();
        $userRepository = static::$container->get(UserRepository::class);

        $testUser = $userRepository->findOneByPseudo('user1');

        $client->loginUser($testUser);
        $client->request('GET', $url);

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function provideUserUrls()
    {
        return [
            ['/'],
            ['/login'],
            ['/register'],
            ['/logout'],
        ];
    }
}