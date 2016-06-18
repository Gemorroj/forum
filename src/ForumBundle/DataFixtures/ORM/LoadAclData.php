<?php

namespace ForumBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\RoleSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;
use ForumBundle\Entity\User;
use ForumBundle\Entity\Forum;
use ForumBundle\Entity\Post;
use ForumBundle\Entity\Topic;


class LoadAclData extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $aclProvider = $this->container->get('security.acl.provider');

        $roles = [
            'usr' => new RoleSecurityIdentity('ROLE_USER'),
            'adm' => new RoleSecurityIdentity('ROLE_ADMIN'),
            'sup' => new RoleSecurityIdentity('ROLE_SUPER_ADMIN'),
        ];

        $usrMask = new MaskBuilder();
        $admMask = new MaskBuilder();
//        $supMask = new MaskBuilder();

        $classes = [
            'user' => [
                'oid' => ObjectIdentity::fromDomainObject(new User()),
                'mask' => [
                    'usr' => $usrMask->add(MaskBuilder::MASK_VIEW)->get(),
                    'adm' => $admMask->add(MaskBuilder::MASK_VIEW)->get(),
                    'sup' => MaskBuilder::MASK_OWNER,
                ],
            ],
            'forum' => [
                'oid' => ObjectIdentity::fromDomainObject(new Forum()),
                'mask' => [
                    'usr' => $usrMask->get(),
                    'adm' => $admMask->get(),
                    'sup' => MaskBuilder::MASK_OWNER,
                ],
            ],
            'topic' => [
                'oid' => ObjectIdentity::fromDomainObject(new Topic()),
                'mask' => [
                    'usr' => $usrMask->add(MaskBuilder::MASK_CREATE)->get(),
                    'adm' => $admMask->add(MaskBuilder::MASK_CREATE)->get(),
                    'sup' => MaskBuilder::MASK_OWNER,
                ],
            ],
            'post' => [
                'oid' => ObjectIdentity::fromDomainObject(new Post()),
                'mask' => [
                    'usr' => $usrMask->get(),
                    'adm' => $admMask->add(MaskBuilder::MASK_EDIT)->get(),
                    'sup' => MaskBuilder::MASK_OWNER,
                ],
            ],
        ];

        foreach ($classes as $class) {
            $acl = $aclProvider->createAcl($class['oid']);

            if (isset($class['mask'])) {
                foreach ($class['mask'] as $role => $mask) {
                    if (isset($roles[$role])) {
                        $acl->insertClassAce($roles[$role], $mask/*, 0, true, PermissionGrantingStrategy::ANY*/);
                    }
                }
            }

            $aclProvider->updateAcl($acl);
        }
    }

    public function getOrder()
    {
        return 1;
    }
}
