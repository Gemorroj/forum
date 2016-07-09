<?php
namespace ForumBundle\Security;

use ForumBundle\Entity\Post;
use ForumBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class PostVoter extends Voter
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

        if (!$subject instanceof Post) {
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

    private function canView(Post $post, TokenInterface $token)
    {
        return true;
    }

    private function canEdit(Post $post, TokenInterface $token)
    {
        if (!($token->getUser() instanceof User)) {
            return false;
        }
        return $token->getUser() === $post->getUser();
    }

    private function canCreate(Post $post, TokenInterface $token)
    {
        if (!($token->getUser() instanceof User)) {
            return false;
        }
        return $this->decisionManager->decide($token, array('ROLE_USER'));
    }

    private function canDelete(Post $post, TokenInterface $token)
    {
        if (!($token->getUser() instanceof User)) {
            return false;
        }
        if ($token->getUser() === $post->getUser()) {
            return true;
        }

        return $this->decisionManager->decide($token, array('ROLE_ADMIN'));
    }
}
