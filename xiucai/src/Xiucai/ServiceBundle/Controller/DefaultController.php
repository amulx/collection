<?php

namespace Xiucai\ServiceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Xiucai\ServiceBundle\CornMeeting\CornMeeting;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        $logger=$this->get('logger');
        $webService = new CornMeeting("appType", "uiType", "xorEnKey", "webServiceUrl");
        var_dump($webService->getXorEnKey());

        return $this->render('ServiceBundle:Default:index.html.twig', array('name' => $name));
    }
}
