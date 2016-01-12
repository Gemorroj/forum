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
        $uri = self::$kernel->getContainer()->get('router')->generate('forum_show', ['id' => 1, 'page' => PHP_INT_MAX]);

        $crawler = self::$client->request('GET', $uri);

        $this->assertEquals(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());

        $this->assertContains('Test topic 1 in PHP forum', $crawler->filter('li > a')->last()->text());
    }

    public function testPost()
    {
        $uri = self::$kernel->getContainer()->get('router')->generate('topic_show', ['id' => 1, 'page' => PHP_INT_MAX]);

        $crawler = self::$client->request('GET', $uri);

        $this->assertEquals(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());

        $this->assertContains('Test post 1', $crawler->filter('li')->last()->text());
    }

    public function testPostNew()
    {
        $uri = self::$kernel->getContainer()->get('router')->generate('post_new', ['id' => 1]);

        $crawler = self::$client->request('GET', $uri);

        $this->assertEquals(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());

        $this->assertContains('Текст', $crawler->filter('label')->first()->text());
    }

    public function testPostNewAdd()
    {
        $text = 'тест пост';

        $uri = self::$kernel->getContainer()->get('router')->generate('post_new', ['id' => 1]);

        $crawler = self::$client->request('GET', $uri);

        $form = $crawler->selectButton('post_submit')->form(['post[text]' => $text]);

        self::$client->submit($form);

        $this->assertTrue(self::$client->getResponse()->isRedirection());

        $crawler = self::$client->followRedirect();

        $this->assertContains($text, $crawler->filter('ul > li')->first()->text());
    }
}
