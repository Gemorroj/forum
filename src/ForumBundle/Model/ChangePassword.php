<?php

namespace ForumBundle\Model;


class ChangePassword
{
    /**
     * @var string
     * Текущий пароль пользователя
     */
    private $currentPlainPassword;

    /**
     * @var string
     * Новый пароль пользователя
     */
    private $plainPassword;


    /**
     * @param mixed $currentPassword
     * @return ChangePassword
     */
    public function setCurrentPlainPassword($currentPassword)
    {
        $this->currentPlainPassword = $currentPassword;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCurrentPlainPassword()
    {
        return $this->currentPlainPassword;
    }


    /**
     * @param mixed $plainPassword
     * @return ChangePassword
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }
}
