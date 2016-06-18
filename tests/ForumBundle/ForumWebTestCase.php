<?php

namespace Tests\ForumBundle;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DomCrawler\Crawler;

abstract class ForumWebTestCase extends WebTestCase
{
    /**
     * @var Container
     */
    protected static $container;
    /**
     * @var Client
     */
    protected static $client;


    protected function setUp()
    {
        if (!self::$container) {
            self::$client = static::createClient();
            self::$container = self::$client->getContainer();
            self::loginAsUser();
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
     * @return Crawler
     * @throws \Exception
     */
    protected static function loginAsAdmin()
    {
        throw new \Exception('Not implemented');
    }


    /**
     * @return Crawler
     * @throws \Exception
     */
    protected static function loginAsAnon()
    {
        throw new \Exception('Not implemented');
    }
}
