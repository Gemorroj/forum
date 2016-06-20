<?php

namespace ForumBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use ForumBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUserData extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $users = [
            'test' => [
                'plainPassword' => '12345678',
                'sex' => null,
            ],
            'aaaa' => [
                'plainPassword' => '11111111',
                'sex' => User::SEX_MALE,
            ],
            'bbbb' => [
                'plainPassword' => '22222222',
                'sex' => User::SEX_MALE,
            ],
            'cccc' => [
                'plainPassword' => '33333333',
                'sex' => User::SEX_FEMALE,
            ],
            'dddd' => [
                'plainPassword' => '44444444',
                'sex' => User::SEX_FEMALE,
            ],
        ];

        foreach($users as $username => $data) {
            $user = new User();
            $user->setUsername($username);
            $user->setPlainPassword($data['plainPassword']);
            $user->setSex($data['sex']);

            $manager->persist($user);
            $manager->flush();

            if ('test' == $username) {
                $this->setReference('user', $user);
            }
        }
    }

    public function getOrder()
    {
        return 1;
    }
}
