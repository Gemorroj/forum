<?php

namespace Tests\ForumBundle\Controller;

use Tests\ForumBundle\ForumWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ForumControllerTest extends ForumWebTestCase
{
    public function testIndex()
    {
        $text = ['PHP', 'MySQL'];
        $uri = self::$kernel->getContainer()->get('router')->generate('index');

        $crawler = self::$client->request('GET', $uri);

        $this->assertEquals(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());

        $this->assertContains($text[0], $crawler->filter('li > a')->eq(0)->text());
        $this->assertContains($text[1], $crawler->filter('li > a')->eq(1)->text());
    }

    public function testShow()
    {
        $text = 'Test topic 1 in PHP forum';

        $uri = self::$kernel->getContainer()->get('router')->generate('forum_show', ['id' => 1, 'page' => PHP_INT_MAX]);

        $crawler = self::$client->request('GET', $uri);

        $this->assertEquals(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());

        $this->assertContains($text, $crawler->filter('li')->last()->text());
    }
}
