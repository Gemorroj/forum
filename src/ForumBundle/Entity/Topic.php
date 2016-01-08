<?php

namespace ForumBundle\Entity;

/**
 * Topic
 */
class Topic
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var boolean
     */
    private $closed = '0';

    /**
     * @var integer
     */
    private $countPosts = '0';

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \ForumBundle\Entity\Forum
     */
    private $forum;


    /**
     * Set title
     *
     * @param string $title
     *
     * @return Topic
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
     * Set closed
     *
     * @param boolean $closed
     *
     * @return Topic
     */
    public function setClosed($closed)
    {
        $this->closed = $closed;

        return $this;
    }

    /**
     * Get closed
     *
     * @return boolean
     */
    public function getClosed()
    {
        return $this->closed;
    }

    /**
     * Set countPosts
     *
     * @param integer $countPosts
     *
     * @return Topic
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set forum
     *
     * @param \ForumBundle\Entity\Forum $forum
     *
     * @return Topic
     */
    public function setForum(\ForumBundle\Entity\Forum $forum = null)
    {
        $this->forum = $forum;

        return $this;
    }

    /**
     * Get forum
     *
     * @return \ForumBundle\Entity\Forum
     */
    public function getForum()
    {
        return $this->forum;
    }
}

