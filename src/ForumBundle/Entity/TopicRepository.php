<?php
namespace ForumBundle\Entity;

use Doctrine\ORM\EntityRepository;

class TopicRepository extends EntityRepository
{
    /**
     * @return \Doctrine\ORM\Query
     */
    public function getListQuery()
    {
        return $this->getEntityManager()->createQuery('SELECT t FROM ForumBundle\Entity\Topic t ORDER BY t.id DESC');
    }
}
