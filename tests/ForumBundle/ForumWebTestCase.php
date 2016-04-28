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
     * @var Crawler
     */
    protected static $crawler;
    /**
     * @var Container
     */
    protected static $container;

    protected function setUp()
    {
        if (!self::$container) {
            self::$client = static::createClient();
            self::$container = self::$client->getContainer();
            self::$crawler = static::login();
        }
    }

    public static function login()
    {
        $uri = self::$container->get('router')->generate('login_route');

        $crawler = self::$client->request('GET', $uri);

        $form = $crawler->selectButton('login_btn')->form(['_username' => 'test', '_password' => '12345678']);

        self::$client->submit($form);

        self::assertTrue(self::$client->getResponse()->isRedirection());

        return self::$client->followRedirect();
    }
}
