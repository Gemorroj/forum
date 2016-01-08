<?php

namespace ForumBundle\Controller;

use ForumBundle\Entity\Forum;
use ForumBundle\Entity\Topic;
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

    public function forumAction(Forum $forum)
    {
        $topics = $this->getDoctrine()->getRepository('ForumBundle:Topic')->findByForum($forum->getId());

        return $this->render('@Forum/forum.html.twig', [
            'forum' => $forum,
            'topics' => $topics
        ]);
    }

    public function topicAction(Topic $topic)
    {
        $posts = $this->getDoctrine()->getRepository('ForumBundle:Post')->findByTopic($topic->getId());

        return $this->render('@Forum/topic.html.twig', [
            'topic' => $topic,
            'posts' => $posts
        ]);
    }
}
