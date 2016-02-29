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
        $text = ['0 / 0', '1 / 2'];

        $testForumCounter = function ($text) {
            $uri = self::$container->get('router')->generate('index');
            $crawler = self::$client->request('GET', $uri);
            $this->assertEquals(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());
            $this->assertEquals($text, $crawler->filter('a > span')->eq(1)->text());
        };

        $testTopicCounter = function ($text) {
            $uri = self::$container->get('router')->generate('forum_show', ['id' => 2, 'page' => PHP_INT_MAX]);
            $crawler = self::$client->request('GET', $uri);
            $this->assertEquals(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());
            $this->assertEquals($text, $crawler->filter('span')->eq(1)->text());
        };

        $testForumCounter($text[0]);
        //\Add
        $title = sprintf('Тест топика #%d', mt_rand());
        $message = sprintf('Тест сообщения #%d', mt_rand());

        $uri = self::$container->get('router')->generate('forum_show', ['id' => 2]);

        $crawler = self::$client->request('GET', $uri);

        $form = $crawler->selectButton('topic_post_submit')->form(['topic[topic-title]' => $title, 'topic[post][text]' => $message]);

        self::$client->submit($form);

        $this->assertTrue(self::$client->getResponse()->isRedirection());

        $crawler = self::$client->followRedirect();

        $this->assertContains($title, $crawler->filter('title')->text());
        $this->assertContains($message, $crawler->filter('li')->eq(1)->text());
        //~Additional
        $additionalPost = sprintf('Тест поста #%d', rand());
        $uri = self::$container->get('router')->generate('topic_show', ['id' => 3]);
        $crawler = self::$client->request('GET', $uri);
        $form = $crawler->selectButton('post_submit')->form(['post[text]' => $additionalPost]);
        self::$client->submit($form);
        $this->assertTrue(self::$client->getResponse()->isRedirection());
        $crawler = self::$client->followRedirect();
        $this->assertContains($additionalPost, $crawler->filter('li')->eq(1)->text());
        ///Add
        $testForumCounter($text[1]);
        $testTopicCounter('2');
    }

    public function testCountersOnDeletePost()
    {
        $text = ['1 / 2', '1 / 1'];

        $testForumCounter = function ($text) {
            $uri = self::$container->get('router')->generate('index');
            $crawler = self::$client->request('GET', $uri);
            $this->assertEquals(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());
            $this->assertEquals($text, $crawler->filter('a > span')->eq(1)->text());
        };

        $testTopicCounter = function ($text) {
            $uri = self::$container->get('router')->generate('forum_show', ['id' => 2, 'page' => PHP_INT_MAX]);
            $crawler = self::$client->request('GET', $uri);
            $this->assertEquals(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());
            $this->assertEquals($text, $crawler->filter('span')->eq(1)->text());
        };

        $testForumCounter($text[0]);
        //\Delete
        $uri = self::$container->get('router')->generate('topic_show', ['id' => 3, 'page' => 1]);

        $crawler = self::$client->request('GET', $uri);

        $this->assertEquals(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());

        $action = $crawler->filter('a.post_delete_button')->eq(0)->attr('data-url'); // First post...

        $form = $crawler->selectButton('post_delete_delete')->form();

        $form->getNode()->setAttribute('action', $action);

        self::$client->submit($form);

        $this->assertTrue(self::$client->getResponse()->isRedirection());

        $crawler = self::$client->followRedirect();

        $this->assertContains('Тест сообщения #', $crawler->filter('li')->eq(1)->text()); // First post...
        ///Delete
        $testForumCounter($text[1]);
        $testTopicCounter('1');
    }

    public function testCountersOnDeleteTopic()
    {
        $text = ['1 / 1', '0 / 0'];

        $testForumCounter = function ($text) {
            $uri = self::$container->get('router')->generate('index');
            $crawler = self::$client->request('GET', $uri);
            $this->assertEquals(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());
            $this->assertEquals($text, $crawler->filter('a > span')->eq(1)->text());
        };

        $testForumCounter($text[0]);
        //\Delete
        $uri = self::$container->get('router')->generate('forum_show', ['id' => 2, 'page' => 1]);

        $crawler = self::$client->request('GET', $uri);

        $this->assertEquals(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());

        $action = $crawler->filter('a.topic_delete_button')->eq(0)->attr('data-url'); // First topic...

        $form = $crawler->selectButton('topic_delete_delete')->form();

        $form->getNode()->setAttribute('action', $action);

        self::$client->submit($form);

        $this->assertTrue(self::$client->getResponse()->isRedirection());

        $crawler = self::$client->followRedirect();

        $this->assertContains('Пусто', $crawler->filter('li')->text()); // First topic...
        ///Delete
        $testForumCounter($text[1]);
    }
}
