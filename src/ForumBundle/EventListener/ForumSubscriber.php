<?php

namespace ForumBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use ForumBundle\Entity\Topic;
use ForumBundle\Entity\Post;
use ForumBundle\Entity\Forum;

class ForumSubscriber implements EventSubscriber
{
    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return array(
            'postPersist',
            'postRemove',
        );
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        switch (true) {
            case $entity instanceof Post:
                $this->updateNumberOfPostsOnTopic($args, +1);
                $this->updateNumberOfPostsOnForum($args, +1);
                break;
            case $entity instanceof Topic:
                $this->updateNumberOfTopicsOnForum($args, +1);
                break;
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        switch (true) {
            case $entity instanceof Post:
                $this->updateNumberOfPostsOnTopic($args, -1);
                $this->updateNumberOfPostsOnForum($args, -1);
                break;
            case $entity instanceof Topic:
                $this->resetNumberOfPostsOnForum($args);
                $this->updateNumberOfTopicsOnForum($args, -1);
                break;
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    private function resetNumberOfPostsOnForum(LifecycleEventArgs $args)
    {
        $em = $args->getEntityManager();

        /** @var Topic $topic */
        $topic = $args->getEntity();
        $forum = $topic->getForum();
        $forum->setCountPosts($forum->getCountPosts() - $topic->getCountPosts());

        $em->persist($forum);
        $em->flush();
    }

    /**
     * @param LifecycleEventArgs $args
     * @param int $shift
     */
    private function updateNumberOfPostsOnTopic(LifecycleEventArgs $args, $shift)
    {
        $em = $args->getEntityManager();

        /** @var Topic $topic */
        $topic = $args->getEntity()->getTopic();
        $topic->setCountPosts($topic->getCountPosts() + $shift);

        $em->persist($topic);
        $em->flush();
    }

    /**
     * @param LifecycleEventArgs $args
     * @param int $shift
     */
    private function updateNumberOfPostsOnForum(LifecycleEventArgs $args, $shift)
    {
        $em = $args->getEntityManager();

        /** @var Forum $forum */
        $forum = $args->getEntity()->getTopic()->getForum();
        $forum->setCountPosts($forum->getCountPosts() + $shift);

        $em->persist($forum);
        $em->flush();
    }

    /**
     * @param LifecycleEventArgs $args
     * @param int $shift
     */
    private function updateNumberOfTopicsOnForum(LifecycleEventArgs $args, $shift)
    {
        $em = $args->getEntityManager();

        /** @var Forum $forum */
        $forum = $args->getEntity()->getForum();
        $forum->setCountTopics($forum->getCountTopics() + $shift);

        $em->persist($forum);
        $em->flush();
    }
}
