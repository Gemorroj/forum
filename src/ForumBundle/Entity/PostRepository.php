<?php
namespace ForumBundle\Entity;

use Doctrine\ORM\EntityRepository;

class PostRepository extends EntityRepository
{
    /**
     * @return \Doctrine\ORM\Query
     */
    public function getListQuery()
    {
        return $this->getEntityManager()->createQuery('SELECT p FROM ForumBundle\Entity\Post p ORDER BY p.id DESC');
    }
}
