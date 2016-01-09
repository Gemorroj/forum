<?php
namespace ForumBundle\Entity;

use Doctrine\ORM\EntityRepository;

class PostRepository extends EntityRepository
{
    /**
     * @return \Doctrine\ORM\Query
     */
    public function getListQuery($topic)
    {
        return $this->getEntityManager()
            ->getRepository('ForumBundle:Post')
            ->createQueryBuilder('p')
            ->where('p.topic = :topic')
            ->setParameter('topic', $topic)
            ->orderBy('p.id', 'DESC')
            ->getQuery();
    }
}
