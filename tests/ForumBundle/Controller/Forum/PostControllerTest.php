<?php

namespace Tests\ForumBundle\Controller;

use Tests\ForumBundle\ForumWebTestCase;

class PostControllerTest extends ForumWebTestCase
{
    public function testNew()
    {
        $text = sprintf('Тест поста #%d', rand());

        $uri = self::$kernel->getContainer()->get('router')->generate('topic_show', ['id' => 1]);

        $crawler = self::$client->request('GET', $uri);

        $form = $crawler->selectButton('post_submit')->form(['post[text]' => $text]);

        self::$client->submit($form);

        $this->assertTrue(self::$client->getResponse()->isRedirection());

        $crawler = self::$client->followRedirect();

        $this->assertContains($text, $crawler->filter('li > span')->first()->text());
    }
}
