<?php

namespace ForumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use ForumBundle\Entity\Forum;

/**
 * Forum controller.
 *
 */
class ForumController extends Controller
{
    /**
     * Lists all Forum entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $forums = $em->getRepository('ForumBundle:Forum')->findAll();

        return $this->render('@Forum/forum/index.html.twig', array(
            'forums' => $forums,
        ));
    }

    /**
     * Creates a new Forum entity.
     *
     */
    /*public function newAction(Request $request)
    {
        $forum = new Forum();
        $form = $this->createForm('ForumBundle\Form\ForumType', $forum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($forum);
            $em->flush();

            return $this->redirectToRoute('forum_show', array('id' => $forum->getId()));
        }

        return $this->render('@Forum/forum/new.html.twig', array(
            'forum' => $forum,
            'form' => $form->createView(),
        ));
    }*/

    /**
     * Finds and displays a Forum entity.
     *
     */
    /*public function showAction(Forum $forum)
    {
        $deleteForm = $this->createDeleteForm($forum);

        return $this->render('@Forum/forum/show.html.twig', array(
            'forum' => $forum,
            'delete_form' => $deleteForm->createView(),
        ));
    }*/

    /**
     * Displays a form to edit an existing Forum entity.
     *
     */
    /*public function editAction(Request $request, Forum $forum)
    {
        $deleteForm = $this->createDeleteForm($forum);
        $editForm = $this->createForm('ForumBundle\Form\ForumType', $forum);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($forum);
            $em->flush();

            return $this->redirectToRoute('forum_edit', array('id' => $forum->getId()));
        }

        return $this->render('@Forum/forum/edit.html.twig', array(
            'forum' => $forum,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }*/

    /**
     * Deletes a Forum entity.
     *
     */
    /*public function deleteAction(Request $request, Forum $forum)
    {
        $form = $this->createDeleteForm($forum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($forum);
            $em->flush();
        }

        return $this->redirectToRoute('forum_index');
    }*/

    /**
     * Creates a form to delete a Forum entity.
     *
     * @param Forum $forum The Forum entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    /*private function createDeleteForm(Forum $forum)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('forum_delete', array('id' => $forum->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }*/
}
