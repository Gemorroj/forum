<?php

namespace ForumBundle\Controller\Forum;

use ForumBundle\Entity\User;
use ForumBundle\Form\ChangePasswordType;
use ForumBundle\Form\ProfileEditType;
use ForumBundle\Form\ProfileNewType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;

class ProfileController extends Controller
{
    /**
     * Профиль пользователя
     *
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(User $user)
    {
        $profileEditForm = $this->createForm(ProfileEditType::class, $user, [
            'action' => $this->generateUrl('profile_edit', [
                'id' => $user->getId(),
            ]),
        ]);

        $countUserTopics = $this->getDoctrine()
            ->getRepository('ForumBundle:Topic')
            ->getCountUserTopics($user);
        $countUserPosts = $this->getDoctrine()
            ->getRepository('ForumBundle:Post')
            ->getCountUserPosts($user);

        return $this->render('@Forum/forum/profile.html.twig', [
            'user' => $user,
            'countUserTopics' => $countUserTopics,
            'countUserPosts' => $countUserPosts,
            'profileEditForm' => $profileEditForm->createView(),
        ]);
    }

    public function newAction(Request $request)
    {
        $form = $this->createForm(ProfileNewType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {

                // If username unique

                /** @var User $user */
                $user = $form->getData();

                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                // creating the ACL
                $aclProvider = $this->get('security.acl.provider');
                $userIdentity = ObjectIdentity::fromDomainObject($user);
                $aclUser = $aclProvider->createAcl($userIdentity);

                // retrieving the security identity of the currently logged-in user
                $securityIdentity = UserSecurityIdentity::fromAccount($user);

                // grant owner access
                $aclUser->insertObjectAce($securityIdentity, MaskBuilder::MASK_OWNER);
                $aclProvider->updateAcl($aclUser);

                return $this->redirectToRoute('profile_show', ['id' => $user->getId()]);
            } else {
                foreach ($form->getErrors(true) as $error) {
                    $this->addFlash('error', $error->getMessage());
                }
            }
        }

        return $this->render('@Forum/forum/registration.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function editAction(Request $request, User $user)
    {
        $this->denyAccessUnlessGranted('EDIT', $user, 'Доступ запрещен. Авторизуйтесь для изменения профиля.');

        $form = $this->createForm(ProfileEditType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
            } else {
                foreach ($form->getErrors(true) as $error) {
                    $this->addFlash('error', $error->getMessage());
                }
            }
        }

        return $this->render('@Forum/forum/profile.edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    public function changePasswordAction(Request $request, User $user)
    {
        $this->denyAccessUnlessGranted('EDIT', $user, 'Доступ запрещен. Авторизуйтесь для изменения профиля.');

        $form = $this->createForm(ChangePasswordType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $user->setPlainPassword(
                    $form->get('plainPassword')->getData()
                );

                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                $this->addFlash('notice', 'Пароль успешно изменен');
            } else {
                foreach ($form->getErrors(true) as $error) {
                    $this->addFlash('error', $error->getMessage());
                }
            }
        }

        return $this->render('@Forum/forum/change_password.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}
