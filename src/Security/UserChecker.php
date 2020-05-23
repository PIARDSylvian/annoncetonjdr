<?php

namespace App\Security;

use App\Entity\User as AppUser;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
class UserChecker implements UserCheckerInterface
{
    /**
     * @param UserInterface $user
     */
    public function checkPreAuth(UserInterface $user)
    {
        if (!$user instanceof AppUser) {
            return;
        }
        if ($user->getConfirmToken() != null) {
            throw new CustomUserMessageAuthenticationException(
                'Votre compte n\'est pas encore actif, veuiller verifier vos email et activer votre compte.');
        }

        if ($user->getSuspend()) {
            throw new CustomUserMessageAuthenticationException(
                'Votre compte a été suspendu');
        }
    }
    public function checkPostAuth(UserInterface $user)
    {
        if (!$user instanceof AppUser) {
            return;
        }
    }
}