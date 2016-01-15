<?php

namespace ForumBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use ForumBundle\Entity\Topic;

class LoadTopicData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $forum = $this->getReference('forum');
        $user = $this->getReference('user');
        $countPosts = 25;


        $topic1 = new Topic();
        $topic1->setTitle('Test topic 1 in PHP forum');
        $topic1->setForum($forum);
        $topic1->setCountPosts($countPosts);
        $topic1->setUser($user);
        $manager->persist($topic1);

        $topic2 = new Topic();
        $topic2->setTitle('Test topic 2 in PHP forum');
        $topic2->setForum($forum);
        $topic2->setCountPosts(0);
        $topic2->setUser($user);
        $manager->persist($topic2);

        $manager->flush();

        $this->addReference('topic', $topic1);
    }

    public function getOrder()
    {
        return 3;
    }
}
