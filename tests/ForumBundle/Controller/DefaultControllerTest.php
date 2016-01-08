<?php

namespace Tests\ForumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;

class DefaultControllerTest extends WebTestCase
{
    /**
     * @var Client
     */
    protected static $client;
    protected function setUp()
    {
        self::$client = static::createClient();
    }


    public function testIndex()
    {
        $crawler = self::$client->request('GET', '/');

        $this->assertEquals(200, self::$client->getResponse()->getStatusCode());

        $this->assertContains('PHP', $crawler->filter('li > a')->eq(0)->text());
        $this->assertContains('MySQL', $crawler->filter('li > a')->eq(1)->text());
    }

    public function testTopic()
    {
        $crawler = self::$client->request('GET', '/forum/1');

        $this->assertEquals(200, self::$client->getResponse()->getStatusCode());

        $this->assertContains('Test topic', $crawler->filter('li > a')->text());
    }

    public function testPost()
    {
        $crawler = self::$client->request('GET', '/topic/1');

        $this->assertEquals(200, self::$client->getResponse()->getStatusCode());

        $this->assertContains('Test post', $crawler->filter('li')->eq(1)->text());
    }
}
