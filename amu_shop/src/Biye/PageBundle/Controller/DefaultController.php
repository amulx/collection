<?php

namespace Biye\PageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('BiyePageBundle:Default:index.html.twig', array('name' => $name));
    }
}
