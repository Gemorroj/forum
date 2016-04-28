<?php

namespace ForumBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use ForumBundle\Entity\Topic;
use ForumBundle\Entity\Post;
use ForumBundle\Entity\Forum;
use ForumBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ForumSubscriber implements EventSubscriber
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

////////////////////////////////////////////////////////////////////////////////

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return array(
                // Один раз. Удаление объекта
            //'preRemove',
            'postRemove',

                // Один раз. Создание объекта
            'prePersist',
            'postPersist',

                // Каждый раз. Изменение любого свойства объекта
            'preUpdate',
            //'postUpdate',

            //'postLoad',
            //'loadClassMetadata',
            //'onClassMetadataNotFound',

            //'preFlush',
            //'onFlush',
            //'postFlush',

            //'onClear',
        );
    }

////////////////////////////////////////////////////////////////////////////////

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        switch (true) {
            case $entity instanceof User:
                if ($entity->getPlainPassword()) {
                    $entity->setPassword($this->encodePlainPassword($entity));
                }
                break;
        }
    }

    /**
     * @param PreUpdateEventArgs $args
     */
    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();

        switch (true) {
            case $entity instanceof User:
                if ($entity->getPlainPassword()) {
                    $args->setNewValue('password', $this->encodePlainPassword($entity));
                }
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
     * @param User $user
     * @return string
     */
    private function encodePlainPassword(User $user)
    {
        return $this->container->get('security.password_encoder')
            ->encodePassword($user, $user->getPlainPassword());
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
