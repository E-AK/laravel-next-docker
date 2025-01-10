<?php

namespace App\Security;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class TaskVoter extends Voter
{
    private const EDIT = 'edit';
    private const DELETE = 'delete';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::DELETE], true)
            && $subject instanceof Task;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /**
         * @var User $user
         */
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        if (!$subject instanceof Task) {
            return false;
        }

        return match ($attribute) {
            self::EDIT => $this->canEdit($subject, $user),
            self::DELETE => $this->canDelete($subject, $user),
            default => false,
        };
    }

    private function canEdit(Task $task, User $user): bool
    {
        return $task->getUserId() === $user->getId();
    }

    private function canDelete(Task $task, User $user): bool
    {
        return $task->getUserId() === $user->getId();
    }
}