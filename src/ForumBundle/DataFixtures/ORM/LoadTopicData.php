<?php

namespace ForumBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use ForumBundle\Entity\Topic;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class LoadTopicData extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
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
        $forum = $this->getReference('forum');
        $user = $this->getReference('user');

        $topics = [
            'Test topic 1 in PHP forum' => [],
            'Test topic 2 in PHP forum' => [],
        ];

        foreach($topics as $topicTitle => $data) {
            $topic = new Topic();
            $topic->setTitle($topicTitle);
            $topic->setForum($forum);
            $topic->setUser($user);
            $manager->persist($topic);
            $manager->flush();

            if ('Test topic 1 in PHP forum' == $topicTitle) {
                $this->addReference('topic', $topic);
            }
        }
    }

    public function getOrder()
    {
        return 3;
    }
}
