<?php

namespace ForumBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use ForumBundle\Entity\Topic;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;

class LoadTopicData extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
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
        $forum = $this->getReference('forum');
        $user = $this->getReference('user');
        $countPosts = 0;


        $topic1 = new Topic();
        $topic1->setTitle('Test topic 1 in PHP forum');
        $topic1->setForum($forum);
        $topic1->setCountPosts($countPosts);
        $topic1->setUser($user);
        $manager->persist($topic1);

        $topic2 = new Topic();
        $topic2->setTitle('Test topic 2 in PHP forum');
        $topic2->setForum($forum);
        $topic2->setCountPosts(0);
        $topic2->setUser($user);
        $manager->persist($topic2);

        $manager->flush();

        $aclProvider = $this->container->get('security.acl.provider');
        $topic1Identity = ObjectIdentity::fromDomainObject($topic1);
        $topic2Identity = ObjectIdentity::fromDomainObject($topic2);
        $aclTopic1 = $aclProvider->createAcl($topic1Identity);
        $aclTopic2 = $aclProvider->createAcl($topic2Identity);
        $securityIdentity = UserSecurityIdentity::fromAccount($user);
        $aclTopic1->insertObjectAce($securityIdentity, MaskBuilder::MASK_OWNER);
        $aclTopic2->insertObjectAce($securityIdentity, MaskBuilder::MASK_OWNER);
        $aclProvider->updateAcl($aclTopic1);
        $aclProvider->updateAcl($aclTopic2);

        $this->addReference('topic', $topic1);
    }

    public function getOrder()
    {
        return 3;
    }
}
