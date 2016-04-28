<?php

namespace Tests\ForumBundle\Controller;

use Tests\ForumBundle\ForumWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ProfileControllerTest extends ForumWebTestCase
{
    public function testShow()
    {
        $text = 'test';

        $uri = self::$container->get('router')->generate('profile_show', ['id' => 1]);

        $crawler = self::$client->request('GET', $uri);

        $this->assertEquals(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());

        $this->assertContains($text, $crawler->filter('div#profile_owner')->text());
    }

    public function testCountingUserTopicAndPosts()
    {
        $topics = ['2', '3'];
        $posts = ['25', '26'];

        $testCountingUserTopicAndPosts = function ($countTopics, $countPosts) {
            $uri = self::$container->get('router')->generate('profile_show', ['id' => 1]);
            $crawler = self::$client->request('GET', $uri);
            $this->assertEquals(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());
            $this->assertEquals($countTopics, $crawler->filter('div#count_user_topics')->text());
            $this->assertEquals($countPosts, $crawler->filter('div#count_user_posts')->text());
        };

        $testCountingUserTopicAndPosts($topics[0], $posts[0]);

        // \Add topic with post
        $title = sprintf('Тест топика #%d', rand());
        $message = sprintf('Тест сообщения #%d', rand());

        $uri = self::$container->get('router')->generate('forum_show', ['id' => 2]);

        $crawler = self::$client->request('GET', $uri);

        $form = $crawler->selectButton('topic_post_submit')->form(['topic[topic-title]' => $title, 'topic[post][text]' => $message]);

        self::$client->submit($form);

        $this->assertTrue(self::$client->getResponse()->isRedirection());

        $crawler = self::$client->followRedirect();

        $this->assertContains($title, $crawler->filter('title')->text());
        $this->assertContains($message, $crawler->filter('li')->eq(1)->text());
        // \Add topic with post

        $testCountingUserTopicAndPosts($topics[1], $posts[1]);
    }

    public function testEditProfileAsOwnerAndSexChoice()
    {
        // self::$client = User-Owner = test
        $userAsOwner = [
            'id' => 1,
            'username' => 'test',
        ];
        $sex = [
            'before' => [
                'choice' => 0,
                'label' => 'Не указывать',
            ],
            'after' => [
                'choice' => 1,
                'label' => 'Мужской',
            ],
        ];

        $uri = self::$container->get('router')->generate('profile_show', ['id' => $userAsOwner['id']]);

        $crawler = self::$client->request('GET', $uri);

        $this->assertEquals(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());

        $this->assertEquals($userAsOwner['username'], $crawler->filter('div#profile_owner')->text());

        $this->assertEquals($sex['before']['label'], $crawler->filter('option[selected]')->text());

        $form = $crawler->selectButton('profile_edit_edit')->form([
            'profile_edit[sex]' => 1,
//            'profile_edit[plainPassword]' => 12345678,
        ]);

        self::$client->submit($form);

        $this->assertTrue(self::$client->getResponse()->isRedirection());

        $crawler = self::$client->followRedirect();

        $this->assertEquals($sex['after']['label'], $crawler->filter('option[selected]')->text());
    }

    public function testEditProfileAsUser()
    {
        // self::$client = User-Owner = test
        $profileOwner = [
            'id' => 2,
            'username' => 'aaaa',
        ];
        $sex = [
            'before' => [
                'label' => 'Мужской',
            ],
            'after' => [
                'label' => 'Мужской',
            ],
        ];

        $checkProfileOwner = function ($period) use ($profileOwner, $sex) {
            $uri = self::$container->get('router')->generate('profile_show', ['id' => $profileOwner['id']]);
            $crawler = self::$client->request('GET', $uri);
            $this->assertEquals(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());
            $this->assertEquals($profileOwner['username'], $crawler->filter('div#profile_owner')->text());
            $this->assertEquals($sex[$period]['label'], $crawler->filter('span#sex')->text());
        };

        $checkProfileOwner('before');
        // \Try change aaaa-profile by user-test
        $uri = self::$container->get('router')->generate('profile_show', ['id' => 1]);

        $crawler = self::$client->request('GET', $uri);

        $this->assertEquals(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('profile_edit_edit')->form(['profile_edit[sex]' => 2]); // Женский=2-inChoiceList
        $action = self::$container->get('router')->generate('profile_edit', ['id' => $profileOwner['id']]);
        $form->getNode()->setAttribute('action', $action);

        self::$client->submit($form);

        $this->assertEquals(Response::HTTP_FORBIDDEN, self::$client->getResponse()->getStatusCode());
//        $this->assertTrue(self::$client->getResponse()->isRedirection());
//        $crawler = self::$client->followRedirect();
        $checkProfileOwner('after');
        // \Try change aaaa-profile by user-test
    }

    public function testEditProfileAsGuest()
    {
        // self::$client = User-Owner = test
        // $client = Guest
        $client = static::createClient();

        $profileOwner = [
            'id' => 1,
            'username' => 'test',
        ];
        $sex = [
            'before' => [
                'label' => 'Мужской',
            ],
            'after' => [
                'label' => 'Мужской',
            ],
        ];

        $checkProfileOwner = function ($period) use ($profileOwner, $sex) {
            $uri = self::$container->get('router')->generate('profile_show', ['id' => $profileOwner['id']]);
            $crawler = self::$client->request('GET', $uri);
            $this->assertEquals(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());
            $this->assertEquals($profileOwner['username'], $crawler->filter('div#profile_owner')->text());
            $this->assertEquals($sex[$period]['label'], $crawler->filter('option[selected]')->text());
        };

        $checkProfileOwner('before');
        // \Try change aaaa-profile by guest
        $uri = self::$container->get('router')->generate('profile_show', ['id' => $profileOwner['id']]);

        $crawler = self::$client->request('GET', $uri);

        $this->assertEquals(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('profile_edit_edit')->form(['profile_edit[sex]' => '2']); // Женский=2-inChoiceList

        $client->submit($form);

        $this->assertEquals(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->isRedirection());
        $crawler = $client->followRedirect();
        $this->assertEquals('Авторизация', $crawler->filter('div > h1')->text());

        $checkProfileOwner('after');
        // \Try change aaaa-profile by guest
    }

    public function testNew()
    {
        // \Guest registration
        $client = static::createClient();
        $container = self::$client->getContainer();
        // /Guest

        $user = [
            'profile_new[username]' => 'php_unit',
            'profile_new[plainPassword]' => 12345678,
            'profile_new[sex]' => 1,
        ];

        $uri = $container->get('router')->generate('profile_new');

        $crawler = $client->request('GET', $uri);

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('profile_new_new')->form($user);

        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirection());

        $crawler = $client->followRedirect();

        $this->assertContains($user['profile_new[username]'], $crawler->filter('div#profile_owner')->text());
    }
}
