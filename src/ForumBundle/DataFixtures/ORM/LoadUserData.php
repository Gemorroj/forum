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

        $users = [
            'test' => [
                'plainPassword' => '1234',
                'sex' => null,
            ],
            'aaaa' => [
                'plainPassword' => '1111',
                'sex' => User::SEX_MALE,
            ],
            'bbbb' => [
                'plainPassword' => '2222',
                'sex' => User::SEX_MALE,
            ],
            'cccc' => [
                'plainPassword' => '3333',
                'sex' => User::SEX_FEMALE,
            ],
            'dddd' => [
                'plainPassword' => '4444',
                'sex' => User::SEX_FEMALE,
            ],
        ];

        foreach($users as $username => $data) {
            $user = new User();
            $user->setUsername($username);
            $user->setSex($data['sex']);

            $manager->persist($user);
            $manager->flush();

            if ('test' == $username) {
                $this->setReference('user', $user);
            }

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
