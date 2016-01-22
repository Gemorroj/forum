<?php

namespace ForumBundle\Helper;

use Symfony\Component\Form\Form;

class FormHelper
{
    // [!] Выводит только 1 сообщение
    public static function getErrors(Form $form)
    {
        $messages = [];

        $errors = $form->getErrors(true);
        if (0 < $errors->count()) {
            foreach ($errors as $e) {
                $messages[] = $e->getMessage();
            }
        }

        return $messages;
    }
}
