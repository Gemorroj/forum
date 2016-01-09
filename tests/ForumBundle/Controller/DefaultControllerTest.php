<?php

namespace Tests\ForumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\HttpFoundation\Response;

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
        $uri = self::$kernel->getContainer()->get('router')->generate('index');

        $crawler = self::$client->request('GET', $uri);

        $this->assertEquals(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());

        $this->assertContains('PHP', $crawler->filter('li > a')->eq(0)->text());
        $this->assertContains('MySQL', $crawler->filter('li > a')->eq(1)->text());
    }

    public function testTopic()
    {
        $uri = self::$kernel->getContainer()->get('router')->generate('forum', ['id' => 1]);

        $crawler = self::$client->request('GET', $uri);

        $this->assertEquals(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());

        $this->assertContains('Test topic 1 in PHP forum', $crawler->filter('li > a')->text());
    }

    public function testPost()
    {
        $uri = self::$kernel->getContainer()->get('router')->generate('topic', ['id' => 1, 'page' => 2]);

        $crawler = self::$client->request('GET', $uri);

        $this->assertEquals(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());

        $this->assertContains('Test post 15', $crawler->filter('li')->eq(4)->text());
    }
}
