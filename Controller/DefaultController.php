<?php

namespace Mosaika\RadBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('MosaikaRadBundle:Default:index.html.twig');
    }
}
