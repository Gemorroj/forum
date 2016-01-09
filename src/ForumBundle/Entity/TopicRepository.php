<?php
namespace ForumBundle\Entity;

use Doctrine\ORM\EntityRepository;

class TopicRepository extends EntityRepository
{
    /**
     * @return \Doctrine\ORM\Query
     */
    public function getListQuery($forum)
    {
        return $this->getEntityManager()
            ->getRepository('ForumBundle:Topic')
            ->createQueryBuilder('t')
            ->where('t.forum = :forum')
            ->setParameter('forum', $forum)
            ->orderBy('t.id', 'DESC')
            ->getQuery();
    }
}
