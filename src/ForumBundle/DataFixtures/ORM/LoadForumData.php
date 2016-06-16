<?php

namespace ForumBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use ForumBundle\Entity\Forum;


class LoadForumData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $user = $this->getReference('user');

        $forums = [
            'PHP'   => [],
            'MySQL' => [],
        ];

        foreach($forums as $forumTitle => $data) {
            $forum = new Forum();
            $forum->setTitle($forumTitle);
            $forum->setUser($user);
            $manager->persist($forum);
            $manager->flush();

            if ('PHP' == $forumTitle) {
                $this->setReference('forum', $forum);
            }
        }
    }

    public function getOrder()
    {
        return 3;
    }
}
