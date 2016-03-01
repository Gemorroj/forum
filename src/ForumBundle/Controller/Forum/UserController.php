<?php

namespace ForumBundle\Controller\Forum;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserController extends Controller
{
    /**
     * Профиль пользователя
     *
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($id)
    {
        $user = $this->getDoctrine()->getRepository('ForumBundle:User')->find($id);

        return $this->render('@Forum/forum/profile.html.twig', [
            'user' => $user,
        ]);
    }
}
