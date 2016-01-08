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
        $forumPhp = new Forum();
        $forumPhp->setTitle('PHP');
        $forumPhp->setCountPosts(1);
        $forumPhp->setCountTopics(1);
        $manager->persist($forumPhp);

        $forumMysql = new Forum();
        $forumMysql->setTitle('MySQL');
        $forumMysql->setCountPosts(0);
        $forumMysql->setCountTopics(0);
        $manager->persist($forumMysql);

        $topic = new Topic();
        $topic->setTitle('Test topic');
        $topic->setForum($forumPhp);
        $topic->setCountPosts(1);
        $manager->persist($topic);

        $post = new Post();
        $post->setTopic($topic);
        $post->setText('Test post');
        $manager->persist($post);

        $manager->flush();
    }
}