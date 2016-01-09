<?php

namespace ForumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use ForumBundle\Entity\Forum;
use ForumBundle\Entity\Topic;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;

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
        $topics = $this->getDoctrine()->getRepository('ForumBundle:Topic')->findBy(['forum' => $forum]);

        return $this->render('@Forum/forum.html.twig', [
            'forum' => $forum,
            'topics' => $topics,
        ]);
    }

    /**
     * Сообщения в топике
     *
     * @param Topic $topic
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function topicAction(Topic $topic, $page)
    {
        $qb = $this->getDoctrine()
            ->getManager()
            ->createQueryBuilder()
            ->select('p')
            ->from('ForumBundle:Post', 'p');

        $adapter = new DoctrineORMAdapter($qb);
        $pager = new Pagerfanta($adapter);

        $pager->setAllowOutOfRangePages(true)
            ->setCurrentPage($page);

        return $this->render('@Forum/topic.html.twig', [
            'forum' => $topic->getForum(),
            'topic' => $topic,
            'posts' => $pager,

            'pager' => $pager,
        ]);
    }
}
