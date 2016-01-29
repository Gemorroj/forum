<?php

namespace ForumBundle\Controller\Forum;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use ForumBundle\Form\TopicType;
use ForumBundle\Form\PostType;
use ForumBundle\Entity\Forum as ForumEntity;
use ForumBundle\Entity\Topic;
use ForumBundle\Entity\Post;

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

        $form = $this->createForm(PostType::class, null, [
            'action' => $this->generateUrl('post_add', [
                'id' => $topic->getId(),
            ]),
        ]);

        return $this->render('@Forum/forum/topic.html.twig', [
            'topic' => $topic,
            'posts' => $pager,
            'form' => $form->createView(),
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
     *
     */
    public function editAction(Request $request, Topic $topic)
    {
        $user = $this->getUser();
        if (! ($this->isGranted('ROLE_USER', $user)
            && $user->getId() == $topic->getUser()->getId()
        )) {
            $this->addFlash('error', 'Вы не автор данного поста.');
            return $this->redirectToRoute('forum_show', ['id' => $topic->getForum()->getId()]);
        }

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
     *
     */
    public function deleteAction(Topic $topic)
    {
        $user = $this->getUser();
        if ($this->isGranted('ROLE_USER', $user)
            && $user->getId() == $topic->getUser()->getId()
        ) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($topic);
            $em->flush();
        } else {
            $this->addFlash('error', 'Вы не автор данного топика.');
        }

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
