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
     * Список топиков в форуме
     *
     * @param Forum $forum
     * @param int $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Forum $forum, $page)
    {
        $q = $this->getDoctrine()->getRepository('ForumBundle:Topic')->getListQuery($forum);

        $pager = $this->get('paginate')->paginate($q, $page);

        return $this->render('@Forum/topic/index.html.twig', [
            'forum' => $forum,
            'topics' => $pager,
        ]);
    }
}
