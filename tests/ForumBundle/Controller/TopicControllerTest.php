<?php

namespace Tests\ForumBundle\Controller;

use Tests\ForumBundle\ForumWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TopicControllerTest extends ForumWebTestCase
{
    public function testNew()
    {
        $text = sprintf('Тест топика #%d', rand());

        $uri = self::$kernel->getContainer()->get('router')->generate('topic_new', ['id' => 1]);

        $crawler = self::$client->request('GET', $uri);

        $form = $crawler->selectButton('topic_submit')->form(['topic[title]' => $text]);

        self::$client->submit($form);

        $this->assertTrue(self::$client->getResponse()->isRedirection());

        $crawler = self::$client->followRedirect();

        $this->assertContains($text, $crawler->filter('ul > li')->first()->text());
    }

    public function testShow()
    {
        $text = 'Test post 1';

        $uri = self::$kernel->getContainer()->get('router')->generate('topic_show', ['id' => 1, 'page' => PHP_INT_MAX]);

        $crawler = self::$client->request('GET', $uri);

        $this->assertEquals(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());

        $this->assertContains($text, $crawler->filter('li')->last()->text());
    }
}
