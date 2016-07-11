<?php

namespace ForumBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ForumRepository extends EntityRepository
{
    /**
     * @return array
     */
    public function getStatistics()
    {
        return $this->getEntityManager()
        ->getRepository('ForumBundle:Forum')
        ->createQueryBuilder('f')
        ->select('SUM(f.countTopics) AS countTopics')
            ->addSelect('SUM(f.countPosts) AS countPosts')
        ->getQuery()
        ->getSingleResult();
    }
}
