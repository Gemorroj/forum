<?php

namespace ForumBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use ForumBundle\Entity\Topic;
use ForumBundle\Entity\Post;

class ForumSubscriber implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return array(
            'postPersist',
        );
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        switch(true) {
            case $entity instanceof Post:
                $this->updateNumberOfPostsOnTopic($args, +1);
                $this->updateNumberOfPostsOnForum($args, +1);
                break;
            case $entity instanceof Topic:
                $this->updateNumberOfTopicsOnForum($args, +1);
                break;
        }

        return;
    }

    public function postRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        switch(true) {
            case $entity instanceof Post:
                $this->updateNumberOfPostsOnTopic($args, -1);
                $this->updateNumberOfPostsOnForum($args, -1);
                break;
            case $entity instanceof Topic:
                $this->updateNumberOfTopicsOnForum($args, -1);
                break;
        }

        return;
    }

    public function updateNumberOfPostsOnTopic(LifecycleEventArgs $args, $quantity)
    {
        $em = $args->getEntityManager();

        $topic = $args->getEntity()->getTopic();
        $topic->setCountPosts($topic->getCountPosts() + $quantity);

        $em->persist($topic);
        $em->flush();
    }

    public function updateNumberOfPostsOnForum(LifecycleEventArgs $args, $quantity)
    {
        $em = $args->getEntityManager();

        $forum = $args->getEntity()->getTopic()->getForum();
        $forum->setCountPosts($forum->getCountPosts() + $quantity);

        $em->persist($forum);
        $em->flush();
    }

    public function updateNumberOfTopicsOnForum(LifecycleEventArgs $args, $quantity)
    {
        $em = $args->getEntityManager();

        $forum = $args->getEntity()->getForum();
        $forum->setCountTopics($forum->getCountTopics() + $quantity);

        $em->persist($forum);
        $em->flush();
    }
}