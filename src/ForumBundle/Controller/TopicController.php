<?php

namespace ForumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use ForumBundle\Form\TopicType;

use ForumBundle\Entity\Forum;
use ForumBundle\Entity\Topic;
use ForumBundle\Entity\Post;

/**
 * Topic controller.
 *
 */
class TopicController extends Controller
{
    /**
     * Сообщения в топике
     *
     * @param Topic $topic
     * @param int $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Topic $topic, $page)
    {
        $q = $this->getDoctrine()->getRepository('ForumBundle:Post')->getListQuery($topic);

        $pager = $this->get('paginate')->paginate($q, $page);

        return $this->render('@Forum/post/index.html.twig', [
            'topic' => $topic,
            'posts' => $pager,
        ]);
    }

    /**
     * Creates a new Topic entity.
     *
     */
    public function newAction(Request $request, Forum $forum)
    {
        $topic = new Topic();
        $topic->setForum($forum);
        $form = $this->createForm(TopicType::class, $topic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($topic);
            $em->flush();

            return $this->redirectToRoute('forum_show', array('id' => $forum->getId()));
        }

        return $this->render('@Forum/topic/new.html.twig', array(
            'topic' => $topic,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Topic entity.
     *
     */
    /*public function showAction(Topic $topic)
    {
        $deleteForm = $this->createDeleteForm($topic);

        return $this->render('@Forum/topic/show.html.twig', array(
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
