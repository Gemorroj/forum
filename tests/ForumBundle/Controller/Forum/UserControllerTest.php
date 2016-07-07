<?php

namespace Tests\ForumBundle\Controller;

use Tests\ForumBundle\ForumWebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Данный тест включает тестирование всех действий контроллера.
 * Гость, в случае успешной регистрации, переходит на страницу своего профиля, после чего данная страница будет являться
 * отправной точкой для тестирования остальных страниц.
 */
class UserControllerTest extends ForumWebTestCase
{
    /**
     * Гость, в случае успешной регистрации, будет перенаправлен на страницу своего профиля (отправная точка).
     *
     * @param string $username
     * @param string|integer $password
     * @param integer $sex
     * @param string $role
     * @dataProvider userProvider
     */
    public function testNew($username, $password, $sex, $role)
    {
        self::$crawler = self::$client->request(
            'GET',
            self::$container->get('router')->generate('profile_new')
        );

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
     * Пользователь, кликая кнопку "Смена пароля", переходит на страницу изменения пароля, после чего меняет его.
     *
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
     * Пользователь, кликая кнопку "Пользователи", переходит на страницу списка всех пользователей форума.
     *
     * @depends testChangePassword
     */
    public function testList()
    {
        $text = 'Список пользователей';

        $crawler = self::$client->click(
            self::$crawler->selectLink('Пользователи')->link()
        );

        $this->assertEquals(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());

        $this->assertEquals($text, $crawler->filter('h1.header_title')->text());
    }

    /**
     * Пользователь, кликая кнопку "Пользователи", переходит на страницу профиля первого попавшегося пользователя в этом
     * списке.
     *
     * @depends testList
     */
    public function testShow()
    {
        $crawler = self::$client->click(
            self::$crawler->selectLink('Пользователи')->link()
        );

        $link = $crawler->filter('ul > li')->children()->first()->link();
        $crawler = self::$client->click($link);

        $this->assertEquals(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());

        $this->assertEquals(
            $link->getNode()->firstChild->textContent,
            $crawler->filter('div#profile_owner')->text()
        );
    }

    /**
     * Пользователь, кликая кнопку "Редактировать профиль", приступает к редактированию своего профиля.
     * Обновляется ($crawler) страница профиля пользователя.
     *
     * @param integer $key
     * @param string $value
     * @dataProvider genderProvider
     * @depends testShow
     */
    public function testEdit($key, $value)
    {
        $checkSex = function () {
            return self::$crawler->filter('span#sex')->text();
        };

        $beforeSex = $checkSex();

        $crawler = self::$client->click(
            self::$crawler->selectLink('Редактировать профиль')->link()
        );

        $form = $crawler->selectButton('profile_edit_save')->form([
            'profile_edit[sex]' => $key,
        ]);

        self::$crawler = self::$client->click(
            self::$client->submit($form)
                ->selectLink('Профиль')
                ->link()
        ); // Обновление страницы профиля пользователя

        $this->assertNotEquals($value, $beforeSex);
        $this->assertEquals($value, $checkSex());
    }
}
