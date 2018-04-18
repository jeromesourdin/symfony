<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HelloController extends Controller
{
    /**
     * @Route("/hello/{prenom}", name="hello")
     */
    public function helloAction(Request $request, $prenom)
    {
        return $this->render('default/hello.html.twig', array(
            'prenom' => $prenom,
        ));    }
}
