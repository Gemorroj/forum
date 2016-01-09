<?php

namespace ForumBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use ForumBundle\Entity\Forum;
use ForumBundle\Entity\Topic;
use ForumBundle\Entity\Post;

class LoadForumData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $countPosts = 25;

        $forumPhp = new Forum();
        $forumPhp->setTitle('PHP');
        $forumPhp->setCountPosts($countPosts);
        $forumPhp->setCountTopics(1);
        $manager->persist($forumPhp);

        $forumMysql = new Forum();
        $forumMysql->setTitle('MySQL');
        $forumMysql->setCountPosts(0);
        $forumMysql->setCountTopics(0);
        $manager->persist($forumMysql);

        $topic1 = new Topic();
        $topic1->setTitle('Test topic 1 in PHP forum');
        $topic1->setForum($forumPhp);
        $topic1->setCountPosts($countPosts);
        $manager->persist($topic1);

        $topic2 = new Topic();
        $topic2->setTitle('Test topic 2 in PHP forum');
        $topic2->setForum($forumPhp);
        $topic2->setCountPosts(0);
        $manager->persist($topic2);

        for ($i = 1; $i <= $countPosts; $i++) {
            $post = new Post();
            $post->setTopic($topic1);
            $post->setText('Test post ' . $i);
            $manager->persist($post);
        }

        $manager->flush();
    }
}