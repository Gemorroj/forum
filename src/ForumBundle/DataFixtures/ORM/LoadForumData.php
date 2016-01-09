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
        $posts = 25;

        $forumPhp = new Forum();
        $forumPhp->setTitle('PHP');
        $forumPhp->setCountPosts($posts);
        $forumPhp->setCountTopics(1);
        $manager->persist($forumPhp);

        $forumMysql = new Forum();
        $forumMysql->setTitle('MySQL');
        $forumMysql->setCountPosts(0);
        $forumMysql->setCountTopics(0);
        $manager->persist($forumMysql);

        $topic = new Topic();
        $topic->setTitle('Test topic 1 in PHP forum');
        $topic->setForum($forumPhp);
        $topic->setCountPosts($posts);
        $manager->persist($topic);

        $topic = new Topic();
        $topic->setTitle('Test topic 2 in PHP forum');
        $topic->setForum($forumPhp);
        $topic->setCountPosts(0);
        $manager->persist($topic);

        for($i = 1; $posts >= $i; $i++) {
            $post = new Post();
            $post->setTopic($topic);
            $post->setText('Test post ' . $i);
            $manager->persist($post);
        }

        $manager->flush();
    }
}