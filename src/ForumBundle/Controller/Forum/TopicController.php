<?php

namespace ForumBundle\Controller\Forum;

use ForumBundle\Form\PostDeleteType;
use ForumBundle\Form\PostEditType;
use ForumBundle\Form\TopicEditType;
use ForumBundle\Security\TopicVoter;
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
        $this->denyAccessUnlessGranted(TopicVoter::VIEW, $topic, 'Вам отказано в доступе для просмотра топика "' . $topic->getTitle() . '".');

        $q = $this->getDoctrine()->getRepository('ForumBundle:Post')->getListQuery($topic);

        $pager = $this->get('paginate')->paginate($q, $page);

        $postCreateForm = $this->createForm(PostType::class, null, [
            'action' => $this->generateUrl('post_add', [
                'id' => $topic->getId(),
            ]),
        ]);
        $postEditForm = $this->createForm(PostEditType::class);
        $postDeleteForm = $this->createForm(PostDeleteType::class);

        return $this->render('@Forum/forum/topic.html.twig', [
            'topic' => $topic,
            'posts' => $pager,
            'postCreateForm' => $postCreateForm->createView(),
            'postEditForm' => $postEditForm->createView(),
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
        $topic = new Topic();

        $this->denyAccessUnlessGranted(TopicVoter::CREATE, $topic, 'Вам отказано в доступе для создания новых топиков.');

        $user = $this->getUser();
        $form = $this->createForm(TopicType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {

                $data = $form->getData();
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
     * @param Request $request
     * @param Topic $topic
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Topic $topic)
    {
        $this->denyAccessUnlessGranted(TopicVoter::EDIT, $topic, 'Вам отказано в доступе.');

        $form = $this->createForm(TopicEditType::class, $topic);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($topic);
                $em->flush();
            } else {
                foreach ($form->getErrors(true) as $error) {
                    $this->addFlash('error', $error->getMessage());
                }
            }
        }

        return $this->redirectToRoute('forum_show', ['id' => $topic->getForum()->getId()]);
    }

    /**
     * Deletes a Topic entity.
     * @param Topic $topic
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Topic $topic)
    {
        $this->denyAccessUnlessGranted(TopicVoter::DELETE, $topic, 'Вам отказано в доступе.');

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
