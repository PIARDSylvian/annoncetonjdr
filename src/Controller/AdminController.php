<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;

/**
 * @IsGranted("ROLE_ADMIN")
 */
class AdminController extends EasyAdminController
{
    /**
     * @Route("/admin/user/setAdmin", name="bla")
     */
    public function blaAction()
    {
        dump('aaaa');
        die;
    }
}
