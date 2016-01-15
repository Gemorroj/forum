<?php

namespace ForumBundle\Controller\Forum;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
        $form = $this->createForm(TopicType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) { //TODO: catch error

                $data = $form->getData();
                $topic = new Topic();
                $topic->setForum($forum);
                $topic->setTitle($data['topic-title']);

                $post = $data['post'];
                $post->setTopic($topic);

                $em = $this->getDoctrine()->getManager();
                $em->persist($topic);
                $em->persist($post);
                $em->flush();
            }
        }

        return $this->redirectToRoute('topic_show', ['id' => $topic->getId()]);
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
    /*public function editAction(Request $request, Topic $topic)
    {
        $deleteForm = $this->createDeleteForm($topic);
        $editForm = $this->createForm('ForumBundle\Form\TopicType', $topic);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($topic);
            $em->flush();

            return $this->redirectToRoute('topic_edit', array('id' => $topic->getId()));
        }

        return $this->render('@Forum/topic/edit.html.twig', array(
            'topic' => $topic,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }*/

    /**
     * Deletes a Topic entity.
     *
     */
    /*public function deleteAction(Request $request, Topic $topic)
    {
        $form = $this->createDeleteForm($topic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($topic);
            $em->flush();
        }

        return $this->redirectToRoute('topic_index');
    }*/

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