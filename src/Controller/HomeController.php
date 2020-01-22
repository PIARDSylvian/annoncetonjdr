<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController {
    
    /**
     * @Route("/", name="app_home")
     */
    public function home(Request $request)
    {
        return $this->render('home.html.twig');
    }
}
