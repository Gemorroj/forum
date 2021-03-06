<?php

namespace ForumBundle\Controller\Forum;

use ForumBundle\Form\PostEditType;
use ForumBundle\Security\PostVoter;
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
        $this->denyAccessUnlessGranted(PostVoter::CREATE, new Post(), 'Вам отказано в доступе для создания постов.');
        $user = $this->getUser();

        $form = $this->createForm(PostType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {

                /** @var Post $post */
                $post = $form->getData();
                $post->setTopic($topic);
                $post->setUser($user);

                $em = $this->getDoctrine()->getManager();
                $em->persist($post);

                $em->flush();
            } else {
                foreach ($form->getErrors(true) as $error) {
                    $this->addFlash('error', $error->getMessage());
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
     * @param Request $request
     * @param Post $post
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Post $post)
    {
        $this->denyAccessUnlessGranted(PostVoter::EDIT, $post, 'Вам отказано в доступе.');

        $form = $this->createForm(PostEditType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($post);
                $em->flush();
            } else {
                foreach ($form->getErrors(true) as $error) {
                    $this->addFlash('error', $error->getMessage());
                }
            }
        }

        return $this->redirectToRoute('topic_show', ['id' => $post->getTopic()->getId()]);
    }

    /**
     * Deletes a Post entity.
     * @param Post $post
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Post $post)
    {
        $this->denyAccessUnlessGranted(PostVoter::DELETE, $post, 'Вам отказано в доступе.');

        $em = $this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();

        return $this->redirectToRoute('topic_show', ['id' => $post->getTopic()->getId()]);
    }

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
