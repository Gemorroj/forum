<?php

namespace ForumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * Lists all Forum entities.
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $forumRepository = $em->getRepository('ForumBundle:Forum');
        $forums = $forumRepository->findAll();
        $statistics = $forumRepository->getStatistics();

        $countUsers = $em->getRepository('ForumBundle:User')->getCountUsers();

        return $this->render('@Forum/index.html.twig', [
            'forums' => $forums,
            'countUsers' => $countUsers,
            'statistics' => $statistics,
        ]);
    }
}
