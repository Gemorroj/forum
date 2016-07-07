<?php

namespace ForumBundle\Complex;


use Tests\ForumBundle\ForumWebTestCase;


class AccessTest extends ForumWebTestCase
{
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