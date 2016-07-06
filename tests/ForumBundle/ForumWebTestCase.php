<?php

namespace Tests\ForumBundle;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DomCrawler\Crawler;

abstract class ForumWebTestCase extends WebTestCase
{
    /**
     * @var Client
     */
    protected static $client;

    /**
     * @var Container
     */
    protected static $container;

    /**
     * @var Crawler
     */
    protected static $crawler;


    protected function setUp()
    {
        if (!self::$container) {
            self::$client = static::createClient();
            self::$container = self::$client->getContainer();
//            self::loginAsUser();
        }
    }

    /**
     * @return Crawler
     * @throws \Exception
     */
    protected static function loginAsUser()
    {
        $uri = self::$container->get('router')->generate('login_route');

        $crawler = self::$client->request('GET', $uri);

        $form = $crawler->selectButton('login_btn')->form(['_username' => 'test', '_password' => '12345678']);

        self::$client->submit($form);

        self::assertTrue(self::$client->getResponse()->isRedirection());

        return self::$client->followRedirect();
    }


    /**
     * Change tracking
     */
    public function genderProvider()
    {
        return [
//            ['beforeKey', 'beforeValue', 'afterKey', 'afterValue'],
            [0, 'Не указан', 1, 'Мужской'],
            [1, 'Мужской',   2, 'Женский'],
            [2, 'Женский',   0, 'Не указан'],
        ];
    }

    /**
     * Change tracking
     */
    public function passwordProvider()
    {
        return [
//            ['message', 'oldPassword', 'newPassword'],
            ['Пароль успешно изменен', 12345678, 87654321],
            ['Пароль успешно изменен', 87654321, 12345678],
        ];
    }


    /**
     * Fixture
     */
    public function userProvider()
    {
        return [
//            ['username', 'password', 'sex', 'role'],
            ['userAsRole',  12345678, 2, 'ROLE_USER'],
//            ['userAsAdmin', 11111111, 1, 'ROLE_ADMIN'],
//            ['userAsSuper', 22222222, 0, 'ROLE_SUPER_ADMIN'],
        ];
    }

    /**
     * Fixture
     */
    public function forumProvider()
    {
        return [
//            ['forumTitle'],
            ['PHP'],
            ['MySQL'],
        ];
    }

    /**
     * Fixture
     */
    public function topicProvider()
    {
        return [
//            ['topicTitle', 'forumTitle'],
            ['Topic1', 'PHP'],
            ['Topic2', 'MySQL'],
        ];
    }

    /**
     * Fixture
     */
    public function postProvider()
    {
        return [
//        ['postText', 'topicTitle'],
            ['Text #1', 'Topic1'],
            ['Text #2', 'Topic1'],
            ['Text #3', 'Topic2'],
            ['Text #4', 'Topic2'],
        ];
    }
}
