<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use App\Form\PartyType;
use App\Entity\Party;

class HomeController extends AbstractController {
    
    /**
     * @Route("/", name="home")
     */
    public function home(Request $request)
    {
        $party = new Party();
        $form = $this->createForm(PartyType::class, $party);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $party->setOwner($this->getUser());

            // 4) save the User!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($party);
            $entityManager->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            return $this->redirectToRoute('home');
        }

        return $this->render('home.html.twig',array('form' => $form->createView()));
    }
}
