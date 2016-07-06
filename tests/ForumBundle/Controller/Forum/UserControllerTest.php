<?php

namespace Tests\ForumBundle\Controller;

use Tests\ForumBundle\ForumWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends ForumWebTestCase
{
    /**
     * Гость, в случае успешной регистрации, будет перенаправлен на страницу своего профиля.
     * @param string $username
     * @param string|integer $password
     * @param integer $sex
     * @param string $role
     * @dataProvider userProvider
     */
    public function testNew($username, $password, $sex, $role)
    {
        $uri = self::$container->get('router')->generate('profile_new');

        self::$crawler = self::$client->request('GET', $uri);

        $this->assertEquals(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());

        $form = self::$crawler->selectButton('profile_new_registration')->form([
            'profile_new[username]'              => $username,
            'profile_new[plainPassword][first]'  => $password,
            'profile_new[plainPassword][second]' => $password,
        ]);

        self::$client->submit($form);

        $this->assertTrue(self::$client->getResponse()->isRedirection());

        self::$crawler = self::$client->followRedirect();

        $this->assertEquals($username, self::$crawler->filter('div#profile_owner')->text());
    }

    /**
     * Пользователь, находящийся в своем профиле и кликая кнопку "Смена пароля", переходит на страницу изменения
     * пароля, после чего меняет его.
     * @param string $message
     * @param string $oldPassword
     * @param string $newPassword
     * @dataProvider passwordProvider
     * @depends testNew
     */
    public function testChangePassword($message, $oldPassword, $newPassword)
    {
        $crawler = self::$client->click(
            self::$crawler->selectLink('Сменить пароль')->link()
        );

        $this->assertEquals(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('change_password_save')->form([
            'change_password[currentPlainPassword]'  => $oldPassword,
            'change_password[plainPassword][first]'  => $newPassword,
            'change_password[plainPassword][second]' => $newPassword,
        ]);

        $crawler = self::$client->submit($form);

        $this->assertEquals($message, $crawler->filter('p.flash-notice')->text());
    }

    /**
     * Пользователь, находящийся на странице своего профиля и кликая кнопку "Пользователи", переходит на
     * страницу списка всех пользователей форума.
     * @depends testChangePassword
     */
    public function testList()
    {
        $text = 'Список пользователей';

        self::$crawler = self::$client->click(
            self::$crawler->selectLink('Пользователи')->link()
        );

        $this->assertEquals(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());

        $this->assertEquals($text, self::$crawler->filter('h1.header_title')->text());
    }

    /**
     * Пользователь, находящийся на странице списка всех пользователей форума и кликая кнопку "Пользователи",
     * переходит на страницу первого попавшегося пользователя в этом списке.
     * @depends testList
     */
    public function testShow()
    {
        $link = self::$crawler->filter('ul > *')->children()->first()->link();
        self::$crawler = self::$client->click($link);

        $this->assertEquals(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());

        $this->assertEquals(
            $link->getNode()->firstChild->textContent,
            self::$crawler->filter('div#profile_owner')->text()
        );
    }
//
//    public function testCountingUserTopicAndPosts()
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
//
//    public function testEditProfileAsOwnerAndSexChoice()
//    {
//        // self::$client = User-Owner = test
//        $userAsOwner = [
//            'id' => 1,
//            'username' => 'test',
//        ];
//        $sex = [
//            'before' => [
//                'choice' => 0,
//                'label' => 'Не указывать',
//            ],
//            'after' => [
//                'choice' => 1,
//                'label' => 'Мужской',
//            ],
//        ];
//
//        $uri = self::$container->get('router')->generate('profile_edit', ['id' => $userAsOwner['id']]);
//
//        $crawler = self::$client->request('GET', $uri);
//
//        $this->assertEquals(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());
//
//        $this->assertEquals($sex['before']['label'], $crawler->filter('option[selected]')->text());
//
//        $form = $crawler->selectButton('profile_edit_save')->form([
//            'profile_edit[sex]' => 1,
////            'profile_edit[plainPassword]' => 12345678,
//        ]);
//
//        $crawler = self::$client->submit($form);
//
//        $this->assertEquals($sex['after']['label'], $crawler->filter('option[selected]')->text());
//    }
//
//    public function testEditProfileAsUser()
//    {
//        // self::$client = User-Owner = test
//        $profileOwner = [
//            'id' => 2,
//            'username' => 'aaaa',
//        ];
//        $sex = [
//            'before' => [
//                'label' => 'Мужской',
//            ],
//            'after' => [
//                'label' => 'Мужской',
//            ],
//        ];
//
//        $checkProfileOwner = function ($period) use ($profileOwner, $sex) {
//            $uri = self::$container->get('router')->generate('profile_show', ['id' => $profileOwner['id']]);
//            $crawler = self::$client->request('GET', $uri);
//            $this->assertEquals(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());
//            $this->assertEquals($sex[$period]['label'], $crawler->filter('span#sex')->text());
//        };
//
//        $checkProfileOwner('before');
//        // \Try change aaaa-profile by user-test
//        $uri = self::$container->get('router')->generate('profile_edit', ['id' => 1]);
//
//        $crawler = self::$client->request('GET', $uri);
//
//        $this->assertEquals(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());
//
//        $form = $crawler->selectButton('profile_edit_save')->form(['profile_edit[sex]' => 2]); // Женский=2-inChoiceList
//        $action = self::$container->get('router')->generate('profile_edit', ['id' => $profileOwner['id']]);
//        $form->getNode()->setAttribute('action', $action);
//
//        self::$client->submit($form);
//
//        $this->assertEquals(Response::HTTP_FORBIDDEN, self::$client->getResponse()->getStatusCode());
////        $this->assertTrue(self::$client->getResponse()->isRedirection());
////        $crawler = self::$client->followRedirect();
//        $checkProfileOwner('after');
//        // \Try change aaaa-profile by user-test
//    }
//
//    public function testEditProfileAsGuest()
//    {
//        // self::$client = User-Owner = test
//        // $client = Guest
//        $client = static::createClient();
//
//        $profileOwner = [
//            'id' => 1,
//            'username' => 'test',
//        ];
//        $sex = [
//            'before' => [
//                'label' => 'Мужской',
//            ],
//            'after' => [
//                'label' => 'Мужской',
//            ],
//        ];
//
//        $checkProfileOwner = function ($period) use ($profileOwner, $sex) {
//            $uri = self::$container->get('router')->generate('profile_edit', ['id' => $profileOwner['id']]);
//            $crawler = self::$client->request('GET', $uri);
//            $this->assertEquals(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());
//            $this->assertEquals($sex[$period]['label'], $crawler->filter('option[selected]')->text());
//        };
//
//        $checkProfileOwner('before');
//        // \Try change aaaa-profile by guest
//        $uri = self::$container->get('router')->generate('profile_edit', ['id' => $profileOwner['id']]);
//
//        $crawler = self::$client->request('GET', $uri);
//
//        $this->assertEquals(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());
//
//        $form = $crawler->selectButton('profile_edit_save')->form(['profile_edit[sex]' => '2']); // Женский=2-inChoiceList
//
//        $client->submit($form);
//
//        $this->assertEquals(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
//        $this->assertTrue($client->getResponse()->isRedirection());
//        $crawler = $client->followRedirect();
//        $this->assertEquals('Авторизация', $crawler->filter('div > h1')->text());
//
//        $checkProfileOwner('after');
//        // \Try change aaaa-profile by guest
//    }
}
