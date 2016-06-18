<?php

namespace ForumBundle\Helper;


use Symfony\Component\Security\Acl\Model\DomainObjectInterface;


abstract class DomainObject implements DomainObjectInterface
{
    abstract public function getId();

    /**
     * @return string
     */
    public function getObjectIdentifier()
    {
        return static::class . '_' . static::getId();
    }
}
