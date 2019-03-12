<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use App\Entity\User;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/forgotten_password", name="forgotten_password")
     */
    public function forgottenPassword(Request $request, UserPasswordEncoderInterface $encoder, \Swift_Mailer $mailer, TokenGeneratorInterface $tokenGenerator): Response
    {
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
                        "url" => $this->generateUrl('home')
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
        
                    $message = (new \Swift_Message('Forgot Password'))
                        ->setFrom('piard.sylvian@gmail.com')
                        ->setTo($user->getEmail())
                        ->setBody(
                            "blablabla voici le token pour reseter votre mot de passe : " . $url,
                            'text/html'
                        );
        
                    $mailer->send($message);
        
                    $this->addFlash('notice', 'Mail envoyé');
                    $response = array( 
                        "code" => 200,
                        "url" => $this->generateUrl('home')
                    );
                    return $url = new JsonResponse($response);
                }
                else {
                    $this->addFlash('danger', 'Bad Data');
                    $response = array( 
                        "code" => 200,
                        "url" => $this->generateUrl('home')
                    );
                    return $url = new JsonResponse($response);
                }
            }
    
            else {
                $this->addFlash('danger', 'Invalid Token');
                $response = array( 
                    "code" => 200,
                    "url" => $this->generateUrl('home')
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
        $submittedToken = $request->request->get('_csrf_token');

        if ($request->isMethod('POST') && ! $this->isCsrfTokenValid('resetPassword', $submittedToken)) {
            $this->addFlash('danger', 'Invalid Csrf');
            return $this->redirectToRoute('home');
        }

        if ($request->isMethod('POST') && strlen($newPassword) >= 8) {
            $entityManager = $this->getDoctrine()->getManager();
 
            $user = $entityManager->getRepository(User::class)->findOneByResetToken($token);

            if ($user === null) {
                $this->addFlash('danger', 'Token Inconnu');
                return $this->redirectToRoute('home');
            }
 
            $user->setResetToken(null);

            $password = $passwordEncoder->encodePassword($user, $newPassword);
            $user->setPassword($password);

            $entityManager->flush();
 
            $this->addFlash('notice', 'Mot de passe mis à jour');
 
            return $this->redirectToRoute('home');
        }else {
 
            return $this->render('security/reset_password.html.twig');
        }
 
    }
}
