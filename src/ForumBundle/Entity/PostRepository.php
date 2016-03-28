<?php

namespace ForumBundle\Entity;

use Doctrine\ORM\EntityRepository;

class PostRepository extends EntityRepository
{
    /**
     * @param Topic $topic
     * @return \Doctrine\ORM\Query
     */
    public function getListQuery(Topic $topic)
    {
        return $this->getEntityManager()
            ->getRepository('ForumBundle:Post')
            ->createQueryBuilder('p')
            ->where('p.topic = :topic')
            ->setParameter('topic', $topic)
            ->orderBy('p.id', 'DESC')
            ->getQuery();
    }

    /**
     * @param User $user
     * @return integer
     */
    public function getCountUserPosts(User $user)
    {
        return $this->getEntityManager()
        ->getRepository('ForumBundle:Post')
        ->createQueryBuilder('p')
        ->select('COUNT(p)')
        ->where('p.user = :user')
        ->setParameter('user', $user)
        ->getQuery()
        ->getSingleScalarResult();
    }
}
