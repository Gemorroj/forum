<?php

namespace ForumBundle\Controller\Forum;

use ForumBundle\Model\ChangePassword;
use ForumBundle\Entity\User;
use ForumBundle\Form\ChangePasswordType;
use ForumBundle\Form\ProfileEditType;
use ForumBundle\Form\ProfileNewType;
use ForumBundle\Security\UserVoter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class UserController extends Controller
{
    /**
     * @param int $page
     * List of all users.
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction($page)
    {
        $this->denyAccessUnlessGranted(UserVoter::VIEW, new User(), 'Вам отказано в доступе просмотра списка пользователей.');

        $q = $this->getDoctrine()->getRepository('ForumBundle:User')->getListQuery();

        $pager = $this->get('paginate')->paginate($q, $page);

        return $this->render('@Forum/forum/profile.list.html.twig', [
            'users' => $pager,
        ]);
    }

    /**
     * Профиль пользователя
     *
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(User $user)
    {
        $this->denyAccessUnlessGranted(UserVoter::VIEW, $user, 'Вам отказано в доступе просмотра профиля пользователя "' . $user->getUsername() . '".');

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
        $this->denyAccessUnlessGranted(UserVoter::CREATE, new User(), 'Вам отказано в доступе для создания новых пользователей.');

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

                // Automatically Authenticating after Registration
                $this->authenticateUser($user);

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

    /**
     * @param User $user
     */
    private function authenticateUser(User $user)
    {
        $credentials = null;
        $firewall    = 'main';

        $token = new UsernamePasswordToken($user, $credentials, $firewall, $user->getRoles());
        $this->get('security.token_storage')->setToken($token);
        $this->get('session')->set('_security_main', $token->serialize());
    }

    public function editAction(Request $request, User $user)
    {
        $this->denyAccessUnlessGranted(UserVoter::EDIT, $user, 'Вам отказано в доступе.');

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
        $this->denyAccessUnlessGranted(UserVoter::EDIT, $user, 'Вам отказано в доступе.');

        $changePassword = new ChangePassword();
        $form = $this->createForm(ChangePasswordType::class, $changePassword);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $user->setPlainPassword($changePassword->getPlainPassword());

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
