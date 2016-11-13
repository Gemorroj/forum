<?php

namespace Tests\ForumBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Tests\ForumBundle\ForumWebTestCase;

class PostControllerTest extends ForumWebTestCase
{
    /**
     * @param array $post
     * @dataProvider postProvider
     */
    public function testAdd($post)
    {
        $crawler = self::$client->click(
            self::$crawler->filter('a.forum_link')->first()->link()
        );
        $crawler = self::$client->click(
            $crawler->filter('a.topic_link')->first()->link()
        );

        $this->assertEquals(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('post_submit')->form([
            'post[text]'  => $post['text'],
        ]);

        self::$client->submit($form);

        $this->assertTrue(self::$client->getResponse()->isRedirection());

        $crawler = self::$client->followRedirect();

        $this->assertContains($post['text'], $crawler->filter('.post_text')->eq(0)->text());
    }

    /**
     * @depends testAdd
     */
    public function testEdit()
    {
        $crawler = self::$client->click(
            self::$crawler->filter('a.forum_link')->first()->link()
        );
        $crawler = self::$client->click(
            $crawler->filter('a.topic_link')->first()->link()
        );

        $this->assertEquals(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());

        $postText = $crawler->filter('.post_text')->eq(0)->text(); // First post in list on first page
        $action = $crawler->filter('a.post_management_button')->eq(0)->attr('data-edit'); // First post...

        $form = $crawler->selectButton('post_edit_edit')->form([
            'post_edit[text]' => $postText . '_changed_'
        ]);

        $form->getNode()->setAttribute('action', $action);

        self::$client->submit($form);

        $this->assertTrue(self::$client->getResponse()->isRedirection());

        $crawler = self::$client->followRedirect();

        $this->assertContains('_changed_', $crawler->filter('.post_text')->eq(0)->text()); // First post...
    }

    /**
     * @depends testEdit
     */
    public function testDelete()
    {
        $crawler = self::$client->click(
            self::$crawler->filter('a.forum_link')->first()->link()
        );
        $crawler = self::$client->click(
            $crawler->filter('a.topic_link')->first()->link()
        );

        $this->assertEquals(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());

        $postText = $crawler->filter('.post_text')->eq(1)->text(); // Second post in list on first page
        $action = $crawler->filter('a.post_management_button')->eq(1)->attr('data-delete'); // Second post...

        $form = $crawler->selectButton('post_delete_delete')->form();

        $form->getNode()->setAttribute('action', $action);

        self::$client->submit($form);

        $this->assertTrue(self::$client->getResponse()->isRedirection());

        $crawler = self::$client->followRedirect();

        $this->assertNotContains($postText, $del = $crawler->filter('.post_text')->eq(1)->text()); // Second post...
    }
}
