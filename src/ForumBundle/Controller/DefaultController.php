<?php

namespace ForumBundle\Controller;

use ForumBundle\Entity\Forum;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        $forums = $this->getDoctrine()->getRepository('ForumBundle:Forum')->findAll();

        return $this->render('@Forum/index.html.twig', [
            'forums' => $forums
        ]);
    }
}
