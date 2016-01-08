<?php

namespace ForumBundle\Controller;

use ForumBundle\Entity\Forum;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $forums = $this->getDoctrine()->getRepository('ForumBundle:Forum')->findAll();

        return $this->render('@Forum/index.html.twig', [
            'forums' => $forums
        ]);
    }

    public function forumAction($id)
    {
        $forum = $this->getDoctrine()->getRepository('ForumBundle:Forum')->find($id);
        $topics = $this->getDoctrine()->getRepository('ForumBundle:Topic')->findByForum($id);

        return $this->render('@Forum/forum.html.twig', [
            'forum' => $forum,
            'topics' => $topics
        ]);
    }

    public function topicAction($id)
    {
        $topic = $this->getDoctrine()->getRepository('ForumBundle:Topic')->find($id);
        $posts = $this->getDoctrine()->getRepository('ForumBundle:Post')->findByTopic($id);

        return $this->render('@Forum/topic.html.twig', [
            'topic' => $topic,
            'posts' => $posts
        ]);
    }
}
