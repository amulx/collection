<?php

namespace Biye\StoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('BiyeStoreBundle:Default:index.html.twig', array('name' => $name));
    }
}
