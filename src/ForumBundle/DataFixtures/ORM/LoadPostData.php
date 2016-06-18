<?php

namespace ForumBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use ForumBundle\Entity\Post;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;

class LoadPostData extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
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
        $topic = $this->getReference('topic');

        $securityIdentity = UserSecurityIdentity::fromAccount($user);

        $countPosts = 25;
        for ($i = 1; $i <= $countPosts; $i++) {
            $post = new Post();
            $post->setTopic($topic);
            $post->setText('Test post ' . $i);
            $post->setUser($user);
            $manager->persist($post);
            $manager->flush();

            $aclPost = $aclProvider->createAcl(ObjectIdentity::fromDomainObject($post));
            $aclPost->insertObjectAce($securityIdentity, MaskBuilder::MASK_OWNER/*, 0, true, PermissionGrantingStrategy::ANY*/);
            $aclProvider->updateAcl($aclPost);
        }
    }

    public function getOrder()
    {
        return 5;
    }
}
