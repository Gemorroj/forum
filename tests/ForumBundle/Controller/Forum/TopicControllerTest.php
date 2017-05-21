<?php

namespace Tests\ForumBundle\Controller;

use Symfony\Component\DomCrawler\Crawler;
use Tests\ForumBundle\ForumWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TopicControllerTest extends ForumWebTestCase
{
    /**
     * @param array $topic
     * @param array $post
     * @dataProvider topicProvider
     */
    public function testAdd($topic, $post)
    {
        $crawler = self::$client->click(
            self::$crawler->filter('a.forum_link')->first()->link()
        );

        $this->assertEquals(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('topic_post_submit')->form([
            'topic[topic-title]' => $topic['title'],
            'topic[post][text]'  => $post['text'],
        ]);

        self::$client->submit($form);

        $this->assertTrue(self::$client->getResponse()->isRedirection());

        $crawler = self::$client->followRedirect();

        $this->assertContains($topic['title'], $crawler->filter('title')->text());
        $this->assertContains($post['text'], $crawler->filter('li')->eq(1)->text());
    }

    /**
     * @param array $topic
     * @dataProvider topicProvider
     * @depends testAdd
     */
    public function testShow($topic)
    {
        $crawler = self::$client->click(
            self::$crawler->filter('a.forum_link')->first()->link()
        );

        $this->assertEquals(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());

        $search = function (Crawler $crawler, array $topic) {
            $domElement = $crawler->filter('.topic_title')->getIterator();
            while ($domElement->valid()) {
                if ($topic['title'] == $domElement->current()->textContent) {
                    return true;
                }
                $domElement->next();
            }
            return false;
        };

        $isFound = false;
        while (true) {
            if (true === $isFound || false === $crawler) {
                break;
            }

            $isFound = $search($crawler, $topic);
            $crawler = $isFound ? false : self::pagination($crawler);
        }

        $this->assertTrue($isFound);
    }

    /**
     * @depends testShow
     */
    public function testEdit()
    {
        $crawler = self::$client->click(
            self::$crawler->filter('a.forum_link')->first()->link()
        );

        $this->assertEquals(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());

        $topicTitle = $crawler->filter('.topic_title')->eq(0)->text(); // First topic in list on first page
        $action = $crawler->filter('a.topic_management_button')->eq(0)->attr('data-edit'); // First topic...

        $form = $crawler->selectButton('topic_edit_edit')->form([
            'topic_edit[title]' => $topicTitle . '_changed_',
        ]);

        $form->getNode()->setAttribute('action', $action);

        self::$client->submit($form);

        $this->assertTrue(self::$client->getResponse()->isRedirection());

        $crawler = self::$client->followRedirect();

        $this->assertContains('_changed_', $crawler->filter('.topic_title')->eq(0)->text()); // First topic...
    }

    /**
     * @depends testEdit
     */
    public function testDelete()
    {
        $crawler = self::$client->click(
            self::$crawler->filter('a.forum_link')->first()->link()
        );

        $this->assertEquals(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());

        $topicText = $crawler->filter('.topic_title')->eq(1)->text(); // Second topic in list on first page
        $action = $crawler->filter('a.topic_management_button')->eq(1)->attr('data-delete'); // Second topic...

        $form = $crawler->selectButton('topic_delete_delete')->form();

        $form->getNode()->setAttribute('action', $action);

        self::$client->submit($form);

        $this->assertTrue(self::$client->getResponse()->isRedirection());

        $crawler = self::$client->followRedirect();

        $this->assertNotContains($topicText, $del = $crawler->filter('.topic_title')->eq(1)->text()); // Second topic...
    }
}
