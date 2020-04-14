<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class HomeController extends AbstractController {
    
    /**
     * @Route("/", name="app_home")
     */
    public function home(Request $request)
    {
        return $this->render('home.html.twig');
    }

    /**
     * @Route("/confirm/{token}", name="app_confirm")
     */
    public function confirm(Request $request, string $token)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->findOneByConfirmToken($token);

        if ($user === null) {
            $this->addFlash('danger', 'Token Inconnu');
            return $this->redirectToRoute('app_home');
        }
 
        $user->setConfirmToken(null);
        $entityManager->flush();

        $this->addFlash('notice', 'Inscription Validé !');

        $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
        $this->get('security.token_storage')->setToken($token);
        $this->get('session')->set('_security_main', serialize($token));

        return $this->redirectToRoute('app_home');
    }
}
