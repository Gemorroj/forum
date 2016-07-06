<?php

namespace ForumBundle\Form;

use ForumBundle\Model\ChangePassword;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('currentPlainPassword', PasswordType::class, [
                'required' => true,
                'label' => 'Действующий пароль',
            ])
            ->add('plainPassword', RepeatedType::class, [
                'required' => true,
                'first_options'  => ['label' => 'Новый пароль'],
                'second_options' => ['label' => 'Повторить новый пароль'],
                'type' => PasswordType::class,
                'invalid_message' => 'Новые пароли должны совпадать!',
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Сохранить новый пароль',
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ChangePassword::class,
            'validation_groups' => ['Default'],
        ]);
    }
}
