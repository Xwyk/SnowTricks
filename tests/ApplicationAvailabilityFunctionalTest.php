<?php

namespace App\Tests;

use App\Entity\Figure;
use App\Entity\User;
use App\Repository\FigureRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class ApplicationAvailabilityFunctionalTest extends WebTestCase
{
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
    }

    /**
     * @dataProvider provideUserUrls
     * @param string $url
     * @param string $method
     * @param int $expectedStatusCode
     * @param bool $authenticatedUser
     */
    public function testPagesAreSuccessful(string $url, string $method, int $expectedStatusCode, bool $authenticatedUser)
    {

        $client = self::createClient();

        if ($authenticatedUser){
            $client->loginUser($this->getFirstUser());
        }

        $client->request($method, $url);

        $this->assertResponseStatusCodeSame($expectedStatusCode);
    }

    public function provideUserUrls()
    {
        //$exampleFigure = $this->getFirstFigure();
        return [
            'home' => ['/','GET', 200, false],
            'home_authenticated' => ['/','GET', 200, true],
            'login' => ['/login','GET', 200, false],
            'login_authenticated' => ['/login','GET', 302, true],
            'register' => ['/register','GET', 200, false],
            'register_authenticated' => ['/register','GET', 302, true],
            'logout' => ['/logout','GET', 302, false],
            'logout_authenticated' => ['/logout','GET', 302, true],
            'figure_add' => ['/figure/add','GET', 302, false],
            'figure_add_authenticated' => ['/figure/add','GET', 200, true],
        ];
    }

    private function getFirstUser(): User
    {
        return (static::$container->get(UserRepository::class)->createQueryBuilder('c')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult())[0];
    }

    private function getFirstFigure(): Figure
    {
        return ($this->createMock(FigureRepository::class)->createQueryBuilder('f')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult())[0];
    }
}