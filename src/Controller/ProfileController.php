<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ProfileFormType;
use App\Form\PasswordFormType;
use App\Entity\User;
use App\Entity\Party;
use App\Entity\Event;
use App\Entity\Commentary;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="app_profile")
     */
    public function profil(Request $request, MailerInterface $mailer, TokenGeneratorInterface $tokenGenerator): Response
    {
        $user = $this->getuser();
        $email = $user->getEmail();

        $form = $this->createForm(ProfileFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getData()->getEmail() != $email) {
                $token = $tokenGenerator->generateToken();
                $user->setConfirmToken($token);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
                

                $url = $this->generateUrl('app_confirm', array('token' => $token), UrlGeneratorInterface::ABSOLUTE_URL);

                $email = (new Email())
                    ->from('piard.sylvian@gmail.com')
                    ->to($user->getEmail())
                    ->subject('confirmation d\'email')
                    ->text(
                        "blablabla voici le lien pour confirmer votre email : " . $url,
                        'text/html'
                    )
                    ->html('<a href="'.$url.'">confirmer votre email</a>')
                ;

                /** @var Symfony\Component\Mailer\SentMessage $sentEmail */
                $sentEmail = $mailer->send($email);

                return $this->redirectToRoute('app_logout');
            }
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
        }

        return $this->render('profile/profile.html.twig', [
            'profileForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/profile/changePassword", name="app_profile_change_password")
     */
    public function changePassword(Request $request, MailerInterface $mailer, TokenGeneratorInterface $tokenGenerator): Response
    {
        $token = $tokenGenerator->generateToken();
        $user = $this->getuser();
        $user->setResetToken($token);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        $url = $this->generateUrl('reset_password', array('token' => $token), UrlGeneratorInterface::ABSOLUTE_URL);

        $email = (new Email())
            ->from('piard.sylvian@gmail.com')
            ->to($user->getEmail())
            ->subject('Change Password request')
            ->text(
                "voici le token pour changer votre mot de passe : " . $url,
                'text/html'
            )
            ->html('<a href="'.$url.'">changer le mot de passe</a>')
        ;

        /** @var Symfony\Component\Mailer\SentMessage $sentEmail */
        $sentEmail = $mailer->send($email);

        $this->addFlash('notice', 'Mail envoyé');
        return $this->redirectToRoute('app_logout');
    }

    /**
     * @Route("/profile/remove", name="app_profile_remove")
     */
    public function remove(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = $this->getUser();
        $password = $user->getPassword();
        $form = $this->createForm(PasswordFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) { 
            $formPassword = $form->getData()->getPassword();
            $user->setPassword($password);
            if ($passwordEncoder->isPasswordValid($user, $formPassword)) {
                $entityManager = $this->getDoctrine()->getManager();

                //remove all partie
                $repository = $this->getDoctrine()->getRepository(Party::class);
                $parties = $repository->findByOwner($user);
                foreach ($parties as $party) {
                    $entityManager->remove($party);
                }

                //remove all events
                $repository = $this->getDoctrine()->getRepository(Event::class);
                $events = $repository->findByOwner($user);
                foreach ($events as $event) {
                    $entityManager->remove($event);
                }

                //remove all com;
                $repository = $this->getDoctrine()->getRepository(Commentary::class);
                $commentaries = $repository->findByOwner($user);
                foreach ($commentaries as $commentary) {
                    $entityManager->remove($commentary);
                }

                //remove user
                $user = $this->getUser();
                $entityManager->remove($user);

                $entityManager->flush();
                $session = $this->get('session');
                $session = new Session();
                $session->invalidate();
                
                return $this->redirectToRoute('app_home');
            }
        }
        return $this->render('profile/remove.html.twig', [
            'passwordForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/profile/party", name="app_profile_party")
     */
    public function profilParty(Request $request): Response
    {
        $repository = $this->getDoctrine()->getRepository(Party::class);
        $parties = $repository->findByOwner($this->getUser());

        foreach ($parties as $partie) {
            dump($partie);
        }

        $subcribes = $repository->findByRegisteredPlayer($this->getUser());

        foreach ($subcribes as $subcribe) {
           dump($subcribe);
        }

        die;

        return $this->render('profile/profile.html.twig', [
            'profileForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/profile/event", name="app_profile_event")
     */
    public function profilEvent(Request $request): Response
    {
        $repository = $this->getDoctrine()->getRepository(Event::class);
        $events = $repository->findByOwner($this->getuser());

        dump($events);
        die;

        return $this->render('profile/profile.html.twig', [
            'profileForm' => $form->createView(),
        ]);
    }
}
