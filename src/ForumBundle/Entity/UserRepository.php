<?php

namespace ForumBundle\Entity;


use Doctrine\ORM\EntityRepository;
//use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;


class UserRepository extends EntityRepository// implements UserLoaderInterface
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
}
