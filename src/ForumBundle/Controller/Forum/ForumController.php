<?php

namespace ForumBundle\Controller\Forum;

use ForumBundle\Form\TopicType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ForumBundle\Entity\Forum as ForumEntity;

class ForumController extends Controller
{
    /**
     * Список топиков в форуме
     *
     * @param ForumEntity $forum
     * @param int $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(ForumEntity $forum, $page)
    {
        $q = $this->getDoctrine()->getRepository('ForumBundle:Topic')->getListQuery($forum);

        $pager = $this->get('paginate')->paginate($q, $page);

        $form = $this->createForm(TopicType::class, null, [
            'action' => $this->generateUrl('topic_add', [
                'id' => $forum->getId(),
            ]),
        ]);

        return $this->render('@Forum/forum/topics.html.twig', [
            'forum' => $forum,
            'topics' => $pager,
            'form' => $form->createView(),
        ]);
    }
}
