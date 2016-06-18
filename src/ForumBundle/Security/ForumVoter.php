<?php
namespace ForumBundle\Security;

use ForumBundle\Entity\Forum;
use ForumBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ForumVoter extends Voter
{
    const VIEW = 'VIEW';
    const EDIT = 'EDIT';
    const CREATE = 'CREATE';
    const DELETE = 'DELETE';

    private $decisionManager;

    public function __construct(AccessDecisionManagerInterface $decisionManager)
    {
        $this->decisionManager = $decisionManager;
    }

    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, array(self::VIEW, self::EDIT, self::CREATE, self::DELETE))) {
            return false;
        }

        if (!$subject instanceof Forum) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        switch ($attribute) {
            case self::VIEW:
                return $this->canView($subject, $token);
                break;
            case self::EDIT:
                return $this->canEdit($subject, $token);
                break;
            case self::CREATE:
                return $this->canCreate($subject, $token);
                break;
            case self::DELETE:
                return $this->canDelete($subject, $token);
                break;
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canView(Forum $forum, TokenInterface $token)
    {
        return true;
    }

    private function canEdit(Forum $forum, TokenInterface $token)
    {
        if (!($token->getUser() instanceof User)) {
            return false;
        }
        return $token->getUser() === $forum->getUser();
    }

    private function canCreate(Forum $forum, TokenInterface $token)
    {
        if (!($token->getUser() instanceof User)) {
            return false;
        }
        return $this->decisionManager->decide($token, array('ROLE_ADMIN'));
    }

    private function canDelete(Forum $forum, TokenInterface $token)
    {
        if (!($token->getUser() instanceof User)) {
            return false;
        }
        if ($token->getUser() === $forum->getUser()) {
            return true;
        }

        return $this->decisionManager->decide($token, array('ROLE_ADMIN'));
    }
}
