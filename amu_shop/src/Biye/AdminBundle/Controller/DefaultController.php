<?php

namespace Biye\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('BiyeAdminBundle:Default:index.html.twig', array('name' => $name));
    }
}
