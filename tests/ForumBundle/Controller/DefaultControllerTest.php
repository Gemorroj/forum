<?php

namespace Tests\ForumBundle\Controller;

use Tests\ForumBundle\ForumWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class DefaultControllerTest extends ForumWebTestCase
{
    public function testIndex()
    {
        $text = ['PHP', 'MySQL'];
        $uri = self::$container->get('router')->generate('index');

        $crawler = self::$client->request('GET', $uri);

        $this->assertEquals(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());

        $this->assertContains($text[0], $crawler->filter('li > a')->eq(0)->text());
        $this->assertContains($text[1], $crawler->filter('li > a')->eq(1)->text());
    }

    public function testCountersOnAddTopicAndPost()
    {
        $text = ['0 / 0', '1 / 1'];

        $title = sprintf('Тест топика #%d', rand());
        $message = sprintf('Тест сообщения #%d', rand());

        $uri = self::$container->get('router')->generate('index');

        $crawler = self::$client->request('GET', $uri);

        $this->assertEquals(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());

        $this->assertContains($text[0], $crawler->filter('a > span')->eq(1)->text());

        $uri = self::$container->get('router')->generate('forum_show', ['id' => 2]);

        $crawler = self::$client->request('GET', $uri);

        $form = $crawler->selectButton('topic_post_submit')->form(['topic[topic-title]' => $title, 'topic[post][text]' => $message]);

        self::$client->submit($form);

        $this->assertTrue(self::$client->getResponse()->isRedirection());

        $crawler = self::$client->followRedirect();

        $this->assertContains($title, $crawler->filter('title')->text());
        $this->assertContains($message, $crawler->filter('li')->eq(1)->text());

        $uri = self::$container->get('router')->generate('index');

        $crawler = self::$client->request('GET', $uri);

        $this->assertEquals(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());

        $this->assertContains($text[1], $crawler->filter('a > span')->eq(1)->text());
    }

    public function testCountersOnDeletePost()
    {
        $text = ['1 / 1', '1 / 0'];

        $uri = self::$container->get('router')->generate('index');

        $crawler = self::$client->request('GET', $uri);

        $this->assertEquals(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());

        $this->assertContains($text[0], $crawler->filter('a > span')->eq(1)->text());

        //\Delete
        $uri = self::$container->get('router')->generate('topic_show', ['id' => 3, 'page' => 1]);

        $crawler = self::$client->request('GET', $uri);

        $this->assertEquals(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());

        $crawler->filter('li')->eq(1)->text(); // First post in list on first page
        $action = $crawler->filter('a.post_delete_button')->eq(0)->attr('data-url'); // First post...

        $form = $crawler->selectButton('post_delete_delete')->form();

        $form->getNode()->setAttribute('action', $action);

        self::$client->submit($form);

        $this->assertTrue(self::$client->getResponse()->isRedirection());

        $crawler = self::$client->followRedirect();

        $this->assertContains('Пусто', $del = $crawler->filter('li')->text()); // First post...
        ///Delete

        $uri = self::$container->get('router')->generate('index');

        $crawler = self::$client->request('GET', $uri);

        $this->assertEquals(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());

        $this->assertContains($text[1], $crawler->filter('a > span')->eq(1)->text());
    }

    public function testCountersOnDeleteTopic()
    {
        $text = ['1 / 0', '0 / 0'];

        $uri = self::$container->get('router')->generate('index');

        $crawler = self::$client->request('GET', $uri);

        $this->assertEquals(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());

        $this->assertContains($text[0], $crawler->filter('a > span')->eq(1)->text());

        //\Delete
        $uri = self::$container->get('router')->generate('forum_show', ['id' => 2, 'page' => 1]);

        $crawler = self::$client->request('GET', $uri);

        $this->assertEquals(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());

        $crawler->filter('span')->eq(0)->text(); // First topic in list on first page
        $action = $crawler->filter('a.topic_delete_button')->eq(0)->attr('data-url'); // First topic...

        $form = $crawler->selectButton('topic_delete_delete')->form();

        $form->getNode()->setAttribute('action', $action);

        self::$client->submit($form);

        $this->assertTrue(self::$client->getResponse()->isRedirection());

        $crawler = self::$client->followRedirect();

        $this->assertContains('Пусто', $del = $crawler->filter('li')->text()); // First topic...
        ///Delete

        $uri = self::$container->get('router')->generate('index');

        $crawler = self::$client->request('GET', $uri);

        $this->assertEquals(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());

        $this->assertContains($text[1], $crawler->filter('a > span')->eq(1)->text());
    }
}
