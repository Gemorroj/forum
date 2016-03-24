<?php

namespace ForumBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use ForumBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;

class LoadUserData extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
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
        $encoder = $this->container->get('security.password_encoder');

        $users = [
            'test' => [
                'plainPassword' => '1234',
                'sex' => null,
            ],
            'aaaa' => [
                'plainPassword' => '1111',
                'sex' => 'm',
            ],
            'bbbb' => [
                'plainPassword' => '2222',
                'sex' => 'm',
            ],
            'cccc' => [
                'plainPassword' => '3333',
                'sex' => 'f',
            ],
            'dddd' => [
                'plainPassword' => '4444',
                'sex' => 'f',
            ],
        ];

        foreach($users as $username => $data) {
            $user = new User();
            $user->setUsername($username);
            $user->setPassword($encoder->encodePassword($user, $data['plainPassword']));
            $user->setSex($data['sex']);

            $manager->persist($user);
            $manager->flush();

            $this->setReference('user', $user);

            // creating the ACL
            $userIdentity = ObjectIdentity::fromDomainObject($user);
            $aclUser = $aclProvider->createAcl($userIdentity);

            // retrieving the security identity of the currently logged-in user
            $securityIdentity = UserSecurityIdentity::fromAccount($user);

            // grant owner access
            $aclUser->insertObjectAce($securityIdentity, MaskBuilder::MASK_OWNER);
            $aclProvider->updateAcl($aclUser);
        }
    }

    public function getOrder()
    {
        return 1;
    }
}
