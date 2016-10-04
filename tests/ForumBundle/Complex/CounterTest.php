<?php

namespace ForumBundle\Complex;


use Tests\ForumBundle\ForumWebTestCase;


class ComplexTest extends ForumWebTestCase
{
    /**
     * @dataProvider topicCounterProvider
     */
    public function testCountTopics()
    {
        // Code...
    }

    /**
     * @dataProvider topicCounterProvider
     * @depends testCountTopics
     */
    public function testCountPosts()
    {
        // Code...
    }

//    public function testTopicCounter()
//    {
//        $topics = ['2', '3'];
//        $posts = ['25', '26'];
//
//        $testCountingUserTopicAndPosts = function ($countTopics, $countPosts) {
//            $uri = self::$container->get('router')->generate('profile_show', ['id' => 1]);
//            $crawler = self::$client->request('GET', $uri);
//            $this->assertEquals(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());
//            $this->assertEquals($countTopics, $crawler->filter('div#count_user_topics')->text());
//            $this->assertEquals($countPosts, $crawler->filter('div#count_user_posts')->text());
//        };
//
//        $testCountingUserTopicAndPosts($topics[0], $posts[0]);
//
//        // \Add topic with post
//        $title = sprintf('Тест топика #%d', rand());
//        $message = sprintf('Тест сообщения #%d', rand());
//
//        $uri = self::$container->get('router')->generate('forum_show', ['id' => 2]);
//
//        $crawler = self::$client->request('GET', $uri);
//
//        $form = $crawler->selectButton('topic_post_submit')->form(['topic[topic-title]' => $title, 'topic[post][text]' => $message]);
//
//        self::$client->submit($form);
//
//        $this->assertTrue(self::$client->getResponse()->isRedirection());
//
//        $crawler = self::$client->followRedirect();
//
//        $this->assertContains($title, $crawler->filter('title')->text());
//        $this->assertContains($message, $crawler->filter('li')->eq(1)->text());
//        // \Add topic with post
//
//        $testCountingUserTopicAndPosts($topics[1], $posts[1]);
//    }
}