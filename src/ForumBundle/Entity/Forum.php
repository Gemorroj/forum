<?php

namespace ForumBundle\Entity;

/**
 * Forum
 */
class Forum
{
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
     * @var integer
     */
    private $position = '0';

    /**
     * @var integer
     */
    private $id;


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
     * Set position
     *
     * @param integer $position
     *
     * @return Forum
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return integer
     */
    public function getPosition()
    {
        return $this->position;
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
}

