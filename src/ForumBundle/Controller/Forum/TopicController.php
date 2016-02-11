<?php

namespace ForumBundle\Controller\Forum;

use ForumBundle\Form\PostDeleteType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use ForumBundle\Form\TopicType;
use ForumBundle\Form\PostType;
use ForumBundle\Entity\Forum as ForumEntity;
use ForumBundle\Entity\Topic;
use ForumBundle\Entity\Post;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;

class TopicController extends Controller
{
    /**
     * Сообщения в топике
     *
     * @param Topic $topic
     * @param int $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Topic $topic, $page)
    {
        $q = $this->getDoctrine()->getRepository('ForumBundle:Post')->getListQuery($topic);

        $pager = $this->get('paginate')->paginate($q, $page);

        $postCreateForm = $this->createForm(PostType::class, null, [
            'action' => $this->generateUrl('post_add', [
                'id' => $topic->getId(),
            ]),
        ]);

        $postDeleteForm = $this->createForm(PostDeleteType::class);

        return $this->render('@Forum/forum/topic.html.twig', [
            'topic' => $topic,
            'posts' => $pager,
            'postCreateForm' => $postCreateForm->createView(),
            'postDeleteForm' => $postDeleteForm->createView(),
        ]);
    }

    /**
     * Creates a new Topic entity.
     *
     * @param Request $request
     * @param ForumEntity $forum
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function newAction(Request $request, ForumEntity $forum)
    {
        $user = $this->getUser();
        if (!$this->isGranted('ROLE_USER', $user)) {
            throw $this->createAccessDeniedException('Доступ запрещен. Авторизуйтесь для добавления новых тем.');
        }

        $form = $this->createForm(TopicType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {

                $data = $form->getData();
                $topic = new Topic();
                $topic->setForum($forum);
                $topic->setTitle($data['topic-title']);
                $topic->setUser($user);

                /** @var Post $post */
                $post = $data['post'];
                $post->setTopic($topic);
                $post->setUser($user);

                $em = $this->getDoctrine()->getManager();
                $em->persist($topic);
                $em->persist($post);

                $em->flush();

                // creating the ACL
                $aclProvider = $this->get('security.acl.provider');
                $topicIdentity = ObjectIdentity::fromDomainObject($topic);
                $postIdentity = ObjectIdentity::fromDomainObject($post);
                $aclTopic = $aclProvider->createAcl($topicIdentity);
                $aclPost = $aclProvider->createAcl($postIdentity);

                // retrieving the security identity of the currently logged-in user
                $securityIdentity = UserSecurityIdentity::fromAccount($this->getUser());

                // grant owner access
                $aclTopic->insertObjectAce($securityIdentity, MaskBuilder::MASK_OWNER);
                $aclPost->insertObjectAce($securityIdentity, MaskBuilder::MASK_OWNER);
                $aclProvider->updateAcl($aclTopic);
                $aclProvider->updateAcl($aclPost);

                return $this->redirectToRoute('topic_show', ['id' => $topic->getId()]);
            } else {
                foreach ($form->getErrors(true) as $error) {
                    $this->addFlash('error', $error->getMessage());
                }
            }
        }

        return $this->redirectToRoute('forum_show', ['id' => $forum->getId()]);
    }

    /**
     * Finds and displays a Topic entity.
     *
     */
    /*public function showAction(Topic $topic)
    {
        $deleteForm = $this->createDeleteForm($topic);

        return $this->render('@Forum/forum/topic.html.twig', array(
            'topic' => $topic,
            'delete_form' => $deleteForm->createView(),
        ));
    }*/

    /**
     * Displays a form to edit an existing Topic entity.
     * @param Request $request
     * @param Topic $topic
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Topic $topic)
    {
        $this->denyAccessUnlessGranted('EDIT', $topic, 'Доступ запрещен. Авторизуйтесь для изменения тем.');

        $form = $this->createFormBuilder($topic)
            ->add('title', TextType::class)
            ->add('submit', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($topic);
                $em->flush();

                return $this->redirectToRoute('forum_show', ['id' => $topic->getForum()->getId()]);
            } else {
                foreach ($form->getErrors(true) as $error) {
                    $this->addFlash('error', $error->getMessage());
                }
            }
        }

        return $this->render('@Forum/forum/topic.edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a Topic entity.
     * @param Topic $topic
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Topic $topic)
    {
        $this->denyAccessUnlessGranted('DELETE', $topic, 'Доступ запрещен. Авторизуйтесь для удаления тем.');

        $em = $this->getDoctrine()->getManager();
        $em->remove($topic);
        $em->flush();

        return $this->redirectToRoute('forum_show', ['id' => $topic->getForum()->getId()]);
    }

    /**
     * Creates a form to delete a Topic entity.
     *
     * @param Topic $topic The Topic entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    /*private function createDeleteForm(Topic $topic)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('topic_delete', array('id' => $topic->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }*/
}
