<?php

namespace ForumBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use ForumBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUserData implements FixtureInterface, ContainerAwareInterface
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
        $user = new User();
        $user->setUsername('test');

        $plainPassword = '1234';
        $encoder = $this->container->get('security.password_encoder');
        $encodedPassword = $encoder->encodePassword($user, $plainPassword);

        $user->setPassword($encodedPassword);

        $manager->persist($user);

        $manager->flush();
    }
}
