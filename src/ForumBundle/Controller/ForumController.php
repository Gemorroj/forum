<?php

namespace ForumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ForumBundle\Entity\Forum;

class ForumController extends Controller
{
    /**
     * Список топиков в форуме
     *
     * @param Forum $forum
     * @param int $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Forum $forum, $page)
    {
        $q = $this->getDoctrine()->getRepository('ForumBundle:Topic')->getListQuery($forum);

        $pager = $this->get('paginate')->paginate($q, $page);

        return $this->render('@Forum/forum/show.html.twig', [
            'forum' => $forum,
            'topics' => $pager,
        ]);
    }
}
