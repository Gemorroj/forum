<?php

namespace ForumBundle\Helper;


use Symfony\Component\Security\Acl\Model\DomainObjectInterface;


abstract class DomainObject implements DomainObjectInterface
{
    abstract public function getId();

    public function getObjectIdentifier()
    {
        return (static::getId()) ? static::getId() : static::class;
    }
}
