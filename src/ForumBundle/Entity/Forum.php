<?php

namespace ForumBundle\Entity;
use ForumBundle\Helper\DomainObject;

/**
 * Forum
 */
class Forum extends DomainObject
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var integer
     */
    private $countTopics = '0';

    /**
     * @var integer
     */
    private $countPosts = '0';

    /**
     * @var \DateTime
     */
    private $createdAt;
    /**
     * @var \ForumBundle\Entity\User
     */
    private $user;

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
     * Set title
     *
     * @param string $title
     *
     * @return Forum
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set countTopics
     *
     * @param integer $countTopics
     *
     * @return Forum
     */
    public function setCountTopics($countTopics)
    {
        $this->countTopics = $countTopics;

        return $this;
    }

    /**
     * Get countTopics
     *
     * @return integer
     */
    public function getCountTopics()
    {
        return $this->countTopics;
    }

    /**
     * Set countPosts
     *
     * @param integer $countPosts
     *
     * @return Forum
     */
    public function setCountPosts($countPosts)
    {
        $this->countPosts = $countPosts;

        return $this;
    }

    /**
     * Get countPosts
     *
     * @return integer
     */
    public function getCountPosts()
    {
        return $this->countPosts;
    }

    /**
     * Set createdAt
     *
     * @return Forum
     */
    public function setCreatedAtValue()
    {
        $this->createdAt = new \DateTime();

        return $this;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime
     *
     * @return Forum
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
     * Get user
     *
     * @return \ForumBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set user
     *
     * @param \ForumBundle\Entity\User $user
     *
     * @return Forum
     */
    public function setUser(\ForumBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }
}
