<?php

namespace App\Security\Voter;

use App\Entity\Headquarter;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class HeadquarterVoter extends Voter
{
    public const EDIT = 'edit';
    public const LIST = 'list';
    public const ADD = 'add';
    public const DELETE = 'delete';

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!$subject instanceof Headquarter) {
            return false;
        }

        if (!in_array($attribute, [self::EDIT, self::LIST, self::ADD, self::DELETE])) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $currentUser = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$currentUser instanceof UserInterface) {
            return false;
        }

        if ($attribute === self::EDIT) {
            return $this->canEdit($subject, $currentUser);
        }

        if ($attribute === self::LIST) {
            return $this->canList($subject, $currentUser);
        }

        if ($attribute === self::ADD) {
            return $this->canAdd($subject, $currentUser);
        }

        if ($attribute === self::DELETE) {
            return $this->canDelete($subject, $currentUser);
        }

        return false;
    }

    private function canEdit(mixed $subject, User $currentUser)
    {
        if (
            $this->security->isGranted('ROLE_SUPER_ADMIN')
            || $this->security->isGranted('ROLE_ADMIN')
        ) {
            return true;
        }

        return false;
    }

    private function canList(mixed $subject, User $currentUser)
    {
        if (
            $this->security->isGranted('ROLE_SUPER_ADMIN')
            || $this->security->isGranted('ROLE_ADMIN')
        ) {
            return true;
        }

        return false;
    }

    private function canAdd(mixed $subject, User $currentUser)
    {
        if (
            $this->security->isGranted('ROLE_SUPER_ADMIN')
            || $this->security->isGranted('ROLE_ADMIN')
        ) {
            return true;
        }

        return false;
    }

    private function canDelete(mixed $subject, User $currentUser)
    {
        if (
            $this->security->isGranted('ROLE_SUPER_ADMIN')
            || $this->security->isGranted('ROLE_ADMIN')
        ) {
            return true;
        }

        return false;
    }
}
