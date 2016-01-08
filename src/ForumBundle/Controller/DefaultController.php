<?php

namespace ForumBundle\Controller;

use ForumBundle\Entity\Forum;
use ForumBundle\Entity\Topic;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function forumAction(Forum $forum)
    {
        $topics = $this->getDoctrine()->getRepository('ForumBundle:Topic')->findByForum($forum);

        return $this->render('@Forum/forum.html.twig', [
            'forum' => $forum,
            'topics' => $topics
        ]);
    }

    /**
     * Сообщения в топике
     *
     * @param Topic $topic
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function topicAction(Topic $topic)
    {
        $posts = $this->getDoctrine()->getRepository('ForumBundle:Post')->findByTopic($topic);
        $forum = $topic->getForum();

        return $this->render('@Forum/topic.html.twig', [
            'forum' => $forum,
            'topic' => $topic,
            'posts' => $posts
        ]);
    }
}
