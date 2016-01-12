<?php

namespace Tests\ForumBundle\Controller;

use Tests\ForumBundle\ForumWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class PostControllerTest extends ForumWebTestCase
{
    public function testPost()
    {
        $uri = self::$kernel->getContainer()->get('router')->generate('post_new', ['id' => 1]);

        $crawler = self::$client->request('GET', $uri);

        $this->assertEquals(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());

        $this->assertContains('Текст', $crawler->filter('label')->first()->text());
    }

    public function testPostNew()
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
