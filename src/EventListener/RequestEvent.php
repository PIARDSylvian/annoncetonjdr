<?php
namespace App\EventListener;

use App\Entity\User;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class RequestListener {

    /**
     * @var \Symfony\Component\Security\Core\SecurityContext
     */
    private $securityContext;

    public function __construct(TokenStorageInterface $tokenStorage, Security $context){
        $this->tokenStorage = $tokenStorage;
        $this->securityContext = $context;
    }

    public function onKernelRequest(RequestEvent  $event)
    {
        // not sure if this is actually needed?
        if (!$event->isMasterRequest()) {
            // don't do anything if it's not the master request
            return;
        }

        // try to get security context and catch the exception in case no firewall was configured (i.e. for the dev tool bar)
        try{
            // trigger only for logged in users
            if($this->securityContext->isGranted('IS_AUTHENTICATED_FULLY')){
                $token = $this->securityContext->getToken();
                /**
                 * @var User $user
                 */
                $user = $token->getUser();

                if($user != null && $user->isSuspend()){
                    $this->tokenStorage->setToken();
                    $event->getRequest()->getSession()->invalidate();
                    $event->getRequest()->getSession()->getFlashBag()->add('danger', 'Votre compte a été suspendu');
                }
            }
        } catch(AuthenticationCredentialsNotFoundException $e){
            // don't do anything here... or do whatever you want.
        }
    }
}
