<?php

namespace ForumBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use ForumBundle\Entity\Topic;
use ForumBundle\Entity\Post;
use ForumBundle\Entity\Forum;
use ForumBundle\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

class ForumSubscriber implements EventSubscriber
{
    protected $passwordEncoder;

    public function __construct(UserPasswordEncoder $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return array(
            'preUpdate',
            'postPersist',
            'postRemove',
        );
    }

////////////////////////////////////////////////////////////////////////////////

    /**
     * @param PreUpdateEventArgs $args
     */
    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();

        switch (true) {
            case $entity instanceof User:
                $this->encodePlainPassword($args);
                break;
        }
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

////////////////////////////////////////////////////////////////////////////////

    /**
     * @param PreUpdateEventArgs $args
     */
    private function encodePlainPassword(PreUpdateEventArgs $args)
    {
        /** @var User $user */
        $user = $args->getEntity();
        $password = $this->passwordEncoder->encodePassword($user, $user->getPlainPassword());
        $user->eraseCredentials();
        $args->setNewValue('password', $password);
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
