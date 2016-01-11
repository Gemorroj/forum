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
     * Lists all Forum entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $forums = $em->getRepository('ForumBundle:Forum')->findAll();

        return $this->render('@Forum/forum/index.html.twig', array(
            'forums' => $forums,
        ));
    }
}
