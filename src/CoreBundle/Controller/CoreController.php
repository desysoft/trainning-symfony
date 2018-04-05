<?php

namespace CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class CoreController extends Controller
{
    public function indexAction()
    {
       
        return $this->render('CoreBundle:Core:index.html.twig');
    }
    
    public function contactAction(Request $request){
        
        $session = $request->getSession();
        $session->getFlashBag()->add("info","La page de contact n’est pas encore disponible");
        return $this->redirectToRoute('core_homepage');
        
    }
}
