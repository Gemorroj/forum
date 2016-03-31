<?php

namespace ForumBundle\Controller\Forum;

use ForumBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use ForumBundle\Form\UserEditType;

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
        $userEditForm = $this->createForm(UserEditType::class, $user, [
            'action' => $this->generateUrl('user_edit', [
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
            'userEditForm' => $userEditForm->createView(),
        ]);
    }

    public function editAction(Request $request, User $user)
    {
        $this->denyAccessUnlessGranted('EDIT', $user, 'Доступ запрещен. Авторизуйтесь для изменения профиля.');

        $form = $this->createForm(UserEditType::class, $user);

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

        return $this->redirectToRoute('user_show', ['id' => $user->getId()]);
    }
}
