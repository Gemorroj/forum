<?php

namespace ForumBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use ForumBundle\Entity\Forum;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\RoleSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;


class LoadForumData extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
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

        $user = $this->getReference('user');

        $roleSecurityIdentity = new RoleSecurityIdentity('ROLE_SUPER_ADMIN');

        $forums = [
            'PHP'   => [],
            'MySQL' => [],
        ];

        foreach($forums as $forumTitle => $data) {
            $forum = new Forum();
            $forum->setTitle($forumTitle);
            $forum->setUser($user);
            $manager->persist($forum);
            $manager->flush();

            if ('PHP' == $forumTitle) {
                $this->setReference('forum', $forum);
            }

            $aclForum = $aclProvider->createAcl(ObjectIdentity::fromDomainObject($forum));
            $aclForum->insertObjectAce($roleSecurityIdentity, MaskBuilder::MASK_OWNER/*, 0, true, PermissionGrantingStrategy::ANY*/);
            $aclProvider->updateAcl($aclForum);
        }
    }

    public function getOrder()
    {
        return 2;
    }
}
