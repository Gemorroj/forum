<?php

namespace ForumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use ForumBundle\Entity\Forum;
use ForumBundle\Entity\Topic;

class DefaultController extends Controller
{
    /**
     * Список форумов
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $forums = $this->getDoctrine()->getRepository('ForumBundle:Forum')->findAll();

        return $this->render('@Forum/index.html.twig', [
            'forums' => $forums
        ]);
    }

    /**
     * Список топиков в форуме
     *
     * @param Forum $forum
     * @param int $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function forumAction(Forum $forum, $page)
    {
        $q = $this->getDoctrine()->getRepository('ForumBundle:Topic')->getListQuery($forum);

        $pager = $this->get('paginate')->paginate($q, $page);

        return $this->render('@Forum/forum.html.twig', [
            'forum' => $forum,
            'topics' => $pager,
        ]);
    }

    /**
     * Сообщения в топике
     *
     * @param Topic $topic
     * @param int $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function topicAction(Topic $topic, $page)
    {
        $q = $this->getDoctrine()->getRepository('ForumBundle:Post')->getListQuery($topic);

        $pager = $this->get('paginate')->paginate($q, $page);

        return $this->render('@Forum/topic.html.twig', [
            'topic' => $topic,
            'posts' => $pager,
        ]);
    }
}
