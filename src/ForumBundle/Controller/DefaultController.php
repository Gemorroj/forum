<?php

namespace ForumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        return $this->render('@Forum/index.html.twig', [
            'items' => [
                ['name' => 'PHP',             'id' => 'cid1', 'count' => 15],
                ['name' => 'SQL',             'id' => 'cid2', 'count' => 12],
                ['name' => 'CSS',             'id' => 'cid3', 'count' => 11],
                ['name' => 'JavaScript',      'id' => 'cid4', 'count' => 13],
                ['name' => 'HTML/xHTML/WML',  'id' => 'cid5', 'count' => 14],
                ['name' => 'Компьютеры/Софт', 'id' => 'cid6', 'count' => 17],
                ['name' => 'Общение',         'id' => 'cid7', 'count' => 16],
            ],
        ]);
    }
}
