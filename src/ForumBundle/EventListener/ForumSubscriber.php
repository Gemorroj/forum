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
            'postRemove',
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
                $this->resetNumberOfPostsOnForum($args);
                $this->updateNumberOfTopicsOnForum($args, -1);
                break;
        }

        return;
    }

    private function resetNumberOfPostsOnForum(LifecycleEventArgs $args)
    {
        $em = $args->getEntityManager();

        $topic = $args->getEntity();
        $forum = $topic->getForum();
        $forum->setCountPosts($forum->getCountPosts() - $topic->getCountPosts());

        $em->persist($forum);
        $em->flush();
    }

    private function updateNumberOfPostsOnTopic(LifecycleEventArgs $args, $shift)
    {
        $em = $args->getEntityManager();

        $topic = $args->getEntity()->getTopic();
        $topic->setCountPosts($topic->getCountPosts() + $shift);

        $em->persist($topic);
        $em->flush();
    }

    private function updateNumberOfPostsOnForum(LifecycleEventArgs $args, $shift)
    {
        $em = $args->getEntityManager();

        $forum = $args->getEntity()->getTopic()->getForum();
        $forum->setCountPosts($forum->getCountPosts() + $shift);

        $em->persist($forum);
        $em->flush();
    }

    private function updateNumberOfTopicsOnForum(LifecycleEventArgs $args, $shift)
    {
        $em = $args->getEntityManager();

        $forum = $args->getEntity()->getForum();
        $forum->setCountTopics($forum->getCountTopics() + $shift);

        $em->persist($forum);
        $em->flush();
    }
}