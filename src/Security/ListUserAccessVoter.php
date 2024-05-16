<?php // src/Security/ListUserAccessVoter.php

namespace App\Security;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ListUserAccessVoter extends Voter
{
    protected function supports(string $attribute, $subject): bool
    {
        return $attribute === 'LIST_USER_ACCESS';
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        // Check if the user has the necessary role to access the /listuser route
        return $token->getUser()->getRoles() && \in_array('ROLE_ADMIN', $token->getUser()->getRoles(), true);
    }
}
