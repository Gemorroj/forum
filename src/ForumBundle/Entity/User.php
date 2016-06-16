<?php

namespace ForumBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * User
 */
class User implements UserInterface
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $username;

    /**
     * @var array
     */
    private $roles = [];

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $plainPassword;

    /**
     * @var string
     */
    private $salt;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var string
     */
    private $sex;
    const SEX_MALE   = 'm';
    const SEX_FEMALE = 'f';

    public function __construct()
    {
        $this->setSalt(\base_convert(\sha1(\uniqid(\mt_rand(), true)), 16, 36));
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set encoded password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get encoded password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set plain password
     *
     * @param string $plainPassword
     *
     * @return User
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;

        // Change some mapped values so preUpdate will get called.
        $this->setPassword(null); // Just blank it out

        return $this;
    }

    /**
     * Get plain password
     *
     * @return string
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * Set salt
     *
     * @param string $salt
     *
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return User
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }


    /**
     * Set createdAt
     *
     * @return User
     */
    public function setCreatedAtValue()
    {
        $this->createdAt = new \DateTime();

        return $this;
    }

    /**
     * Set sex
     *
     * @param string $sex
     *
     * @return User
     */
    public function setSex($sex)
    {
        $this->sex = $sex;

        return $this;
    }

    /**
     * Get sex
     *
     * @return string
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {}

    public function __toString()
    {
        return $this->getUsername();
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {
        // TODO: Назначать ROLE_USER для пользователя в настройках
        $this->roles[] = 'ROLE_USER';

        return $this->roles;
    }

    /**
     * {@inheritdoc}
     */
//    public function setRoles($roles)
//    {
//        return $this->roles = $roles;
//    }

    /**
     * {@inheritdoc}
     */
//    public function promote($role)
//    {
//        $this->roles[] = $role;
//
//        return $this;
//    }

    /**
     * {@inheritdoc}
     */
//    public function demote($role)
//    {
//        if(false !== ($key = array_search($role, $this->roles))) {
//            unset($this->roles[$key]);
//        }
//
//        return $this;
//    }
}
