<?php

namespace ForumBundle\Entity;


use Doctrine\ORM\EntityRepository;


class UserRepository extends EntityRepository
{
    /**
     * @return \Doctrine\ORM\Query
     */
    public function getListQuery()
    {
        return $this->getEntityManager()
            ->getRepository('ForumBundle:User')
            ->createQueryBuilder('u')
            ->orderBy('u.createdAt', 'DESC')
            ->getQuery();
    }

    /**
     * @return integer
     */
    public function getCountUsers()
    {
        return $this->getEntityManager()
            ->getRepository('ForumBundle:User')
            ->createQueryBuilder('u')
            ->select('COUNT(u)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
