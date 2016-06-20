<?php

namespace ForumBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use ForumBundle\Entity\Forum;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class LoadForumData extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
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
        return 2;
    }
}
