<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/forgotten_password", name="forgotten_password")
     */
    public function forgottenPassword(Request $request, UserPasswordEncoderInterface $encoder, MailerInterface $mailer, TokenGeneratorInterface $tokenGenerator): Response
    {
        if($this->getUser() != null) {
            return $this->redirectToRoute('home');
        }
    
        if($request->isXMLHttpRequest()) {
            $submittedToken = $request->query->get('_csrf_token');
            $email = $request->query->get('email');

            if ($this->isCsrfTokenValid('forgotPassword', $submittedToken)) {
                $entityManager = $this->getDoctrine()->getManager();

                $user = $entityManager->getRepository(User::class)->findOneByEmail($email);

                if ($user === null) {
                    $this->addFlash('danger', 'Email Inconnu');
                    $response = array( 
                        "code" => 200,
                        "url" => $this->generateUrl('forgotten_password')
                    );
                    return $url = new JsonResponse($response);
                }

                $userResponse = $request->query->get('response');
                $userBddResponse = $user->getSecretR();
                if(! isset($userResponse)){
                    $question = $user->getSecretQ();
                    $response = array( 
                        "code" => 200,
                        "response" => $this->render('security/forgotten_password_question.html.twig',['question' => $question])->getContent()
                    );

                    return new JsonResponse($response);
                }
                elseif ($userBddResponse == $userResponse) {

                    $token = $tokenGenerator->generateToken();
                    
                    try{
                        $user->setResetToken($token);
                        $entityManager->flush();
                    } catch (\Exception $e) {
                        $this->addFlash('warning', $e->getMessage());
                        return $this->redirectToRoute('forgotten_password');
                    }
                    
                    $url = $this->generateUrl('reset_password', array('token' => $token), UrlGeneratorInterface::ABSOLUTE_URL);

                    $email = (new Email())
                        ->from('piard.sylvian@gmail.com')
                        ->to($user->getEmail())
                        ->subject('Change Password request')
                        ->text(
                            "blablabla voici le token pour changer votre mot de passe : " . $url,
                            'text/html'
                        )
                        ->html('<a href="'.$url.'">changer le mot de passe</a>')
                    ;

                    /** @var Symfony\Component\Mailer\SentMessage $sentEmail */
                    $sentEmail = $mailer->send($email);
        
                    $this->addFlash('notice', 'Mail envoyé');
                    $response = array( 
                        "code" => 200,
                        "url" => $this->generateUrl('app_home')
                    );
                    return $url = new JsonResponse($response);
                }
                else {
                    $this->addFlash('danger', 'Bad Data');
                    $response = array( 
                        "code" => 200,
                        "url" => $this->generateUrl('forgotten_password')
                    );
                    return $url = new JsonResponse($response);
                }
            }
    
            else {
                $this->addFlash('danger', 'Invalid Token');
                $response = array( 
                    "code" => 200,
                    "url" => $this->generateUrl('app_home')
                );
                return $url = new JsonResponse($response);
            }
        }
 
        return $this->render('security/forgotten_password.html.twig');
    }

    /**
     * @Route("/reset_password/{token}", name="reset_password")
     */
    public function resetPassword(Request $request, string $token, UserPasswordEncoderInterface $passwordEncoder)
    {
        $newPassword = $request->request->get('password');
        $repeatPassword = $request->request->get('password2');
        $submittedToken = $request->request->get('_csrf_token');

        if ($request->isMethod('POST') && ! $this->isCsrfTokenValid('resetPassword', $submittedToken)) {
            $this->addFlash('danger', 'Invalid Csrf');
            return $this->redirectToRoute('app_home');
        }

        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->findOneByResetToken($token);

        if ($user === null) {
            $this->addFlash('danger', 'Token Inconnu');
            return $this->redirectToRoute('app_home');
        }

        if ($request->isMethod('POST') && ($newPassword === $repeatPassword) && strlen($newPassword) >= 8 ) {
 
            $user->setResetToken(null);

            $password = $passwordEncoder->encodePassword($user, $newPassword);
            $user->setPassword($password);

            $entityManager->flush();
 
            $this->addFlash('notice', 'Mot de passe mis à jour');
 
            return $this->redirectToRoute('app_home');
        }else {
 
            return $this->render('security/reset_password.html.twig');
        }
 
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }
}
