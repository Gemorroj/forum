<?php

namespace Tests\ForumBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Tests\ForumBundle\ForumWebTestCase;

class PostControllerTest extends ForumWebTestCase
{
    public function testAdd()
    {
        $text = sprintf('Тест поста #%d', rand());

        $uri = self::$container->get('router')->generate('topic_show', ['id' => 1]);

        $crawler = self::$client->request('GET', $uri);

        $form = $crawler->selectButton('post_submit')->form(['post[text]' => $text]);

        self::$client->submit($form);

        $this->assertTrue(self::$client->getResponse()->isRedirection());

        $crawler = self::$client->followRedirect();

        $this->assertContains($text, $crawler->filter('li')->eq(1)->text());
    }

    public function testDelete()
    {
        $uri = self::$container->get('router')->generate('topic_show', ['id' => 1, 'page' => 1]);

        $crawler = self::$client->request('GET', $uri);

        $this->assertEquals(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());

        $postText = $crawler->filter('li')->eq(3)->text(); // Second post in list on first page
        $action = $crawler->filter('#post_delete_button')->eq(1)->attr('data-url'); // Second post in list on first page

        $form = $crawler->selectButton('form_delete')->form();

        $form->getNode()->setAttribute('action', $action);

        self::$client->submit($form);

        $this->assertTrue(self::$client->getResponse()->isRedirection());

        $crawler = self::$client->followRedirect();

        $this->assertNotContains($postText, $del = $crawler->filter('li')->eq(3)->text()); // Second post in list on first page
    }
}
