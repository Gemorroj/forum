<?php
namespace ForumBundle\Pagerfanta;

use Pagerfanta\View\DefaultView;

class View extends DefaultView
{
    protected function createDefaultTemplate()
    {
        return new Template();
    }
}
