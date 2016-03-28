<?php

namespace ForumBundle\Entity;

use Doctrine\ORM\EntityRepository;

class TopicRepository extends EntityRepository
{
    /**
     * @param Forum $forum
     * @return \Doctrine\ORM\Query
     */
    public function getListQuery(Forum $forum)
    {
        return $this->getEntityManager()
            ->getRepository('ForumBundle:Topic')
            ->createQueryBuilder('t')
            ->where('t.forum = :forum')
            ->setParameter('forum', $forum)
            ->orderBy('t.id', 'DESC')
            ->getQuery();
    }

    /**
     * @param User $user
     * @return integer
     */
    public function getCountUserTopics(User $user)
    {
        return $this->getEntityManager()
            ->getRepository('ForumBundle:Topic')
            ->createQueryBuilder('t')
            ->select('COUNT(t)')
            ->where('t.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
