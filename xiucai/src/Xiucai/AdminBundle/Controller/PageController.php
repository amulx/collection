<?php

namespace Xiucai\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PageController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('AdminBundle:Default:index.html.twig', array('name' => $name));
    }
}
