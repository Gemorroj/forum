<?php

namespace Tests\ForumBundle\Controller;

use Tests\ForumBundle\ForumWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class PostControllerTest extends ForumWebTestCase
{
    public function testPostNew()
    {
        $text = sprintf('Тест поста #%d', rand());

        $uri = self::$kernel->getContainer()->get('router')->generate('topic_show', ['id' => 1]);

        $crawler = self::$client->request('GET', $uri);

        $form = $crawler->selectButton('post_submit')->form(['post[text]' => $text]);

        self::$client->submit($form);

        $this->assertTrue(self::$client->getResponse()->isRedirection());

        $crawler = self::$client->followRedirect();

        $this->assertContains($text, $crawler->filter('ul > li')->first()->text());
    }

    public function testPost()
    {
        $text = 'Test post 1';

        $uri = self::$kernel->getContainer()->get('router')->generate('topic_show', ['id' => 1, 'page' => PHP_INT_MAX]);

        $crawler = self::$client->request('GET', $uri);

        $this->assertEquals(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());

        $this->assertContains($text, $crawler->filter('ul > li')->last()->text());
    }
}
