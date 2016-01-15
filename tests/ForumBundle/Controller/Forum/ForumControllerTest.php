<?php

namespace Tests\ForumBundle\Controller;

use Tests\ForumBundle\ForumWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ForumControllerTest extends ForumWebTestCase
{
    public function testShow()
    {
        $text = 'Test topic 1 in PHP forum';

        $uri = self::$kernel->getContainer()->get('router')->generate('forum_show', ['id' => 1, 'page' => PHP_INT_MAX]);

        $crawler = self::$client->request('GET', $uri);

        $this->assertEquals(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());

        $this->assertContains($text, $crawler->filter('li')->last()->text());
    }
}
