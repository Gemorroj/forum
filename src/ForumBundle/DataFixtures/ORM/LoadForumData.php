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

        $forumPhp = new Forum();
        $forumPhp->setTitle('PHP');
        $forumPhp->setUser($user);
        $manager->persist($forumPhp);

        $forumMysql = new Forum();
        $forumMysql->setTitle('MySQL');
        $forumMysql->setUser($user);
        $manager->persist($forumMysql);

        $manager->flush();

        $this->addReference('forum', $forumPhp);
    }

    public function getOrder()
    {
        return 2;
    }
}
