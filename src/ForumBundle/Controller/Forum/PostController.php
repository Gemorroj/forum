<?php

namespace ForumBundle\Controller\Forum;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use ForumBundle\Entity\Topic;
use ForumBundle\Entity\Post;
use ForumBundle\Form\PostType;

/**
 * Post controller.
 *
 */
class PostController extends Controller
{
    /**
     * Creates a new Post entity.
     *
     * @param Request $request
     * @param Topic $topic
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request, Topic $topic)
    {
        $user = $this->getUser();
        if (!$this->isGranted('ROLE_USER', $user)) {
            throw $this->createAccessDeniedException('Доступ запрещен. Авторизуйтесь для добавления сообщений.');
        }

        $form = $this->createForm(PostType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) { //TODO: catch error

                /** @var Post $post */
                $post = $form->getData();
                $post->setTopic($topic);
                $post->setUser($user);

                $em = $this->getDoctrine()->getManager();
                $em->persist($post);
                $em->flush();
            } else {
                // TODO: Выводить сообщения ошибок из $form->getErrors().
                $m = [];
                $errors = $form->getErrors(true);
                if (0 < $errors->count()) {
                    foreach ($errors as $e) {
                        $m[] = $e->getMessage();
                    }

                    $this->get('session')
                        ->getFlashBag()
                        ->set('error', $m);
                }
            }
        }

        return $this->redirectToRoute('topic_show', ['id' => $topic->getId()]);
    }

    /**
     * Finds and displays a Post entity.
     *
     */
    /*public function showAction(Post $post)
    {
        $deleteForm = $this->createDeleteForm($post);

        return $this->render('@Forum/post/show.html.twig', array(
            'post' => $post,
            'delete_form' => $deleteForm->createView(),
        ));
    }*/

    /**
     * Displays a form to edit an existing Post entity.
     *
     */
    /*public function editAction(Request $request, Post $post)
    {
        $deleteForm = $this->createDeleteForm($post);
        $editForm = $this->createForm('ForumBundle\Form\PostType', $post);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            return $this->redirectToRoute('post_edit', array('id' => $post->getId()));
        }

        return $this->render('@Forum/post/edit.html.twig', array(
            'post' => $post,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }*/

    /**
     * Deletes a Post entity.
     *
     */
    /*public function deleteAction(Request $request, Post $post)
    {
        $form = $this->createDeleteForm($post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($post);
            $em->flush();
        }

        return $this->redirectToRoute('post_index');
    }*/

    /**
     * Creates a form to delete a Post entity.
     *
     * @param Post $post The Post entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    /*private function createDeleteForm(Post $post)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('post_delete', array('id' => $post->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }*/
}
