<?php

namespace ForumBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use ForumBundle\Entity\Post;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadPostData extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
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
        $user = $this->getReference('user');
        $topic = $this->getReference('topic');

        $countPosts = 25;
        for ($i = 1; $i <= $countPosts; $i++) {
            $post = new Post();
            $post->setTopic($topic);
            $post->setText('Test post ' . $i);
            $post->setUser($user);
            $manager->persist($post);
            $manager->flush();
        }
    }

    public function getOrder()
    {
        return 4;
    }
}
