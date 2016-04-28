<?php

namespace ForumBundle\Form;

use ForumBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileNewType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Имя пользователя',
                'attr' => [
                    'placeholder' => 'Пароль',
                ]
            ])
            ->add('plainPassword', PasswordType::class, [
                'label' => 'Пароль',
                'attr' => [
                    'placeholder' => 'Пароль',
                ]
            ])
            /*
            ->add('plainPassword_confirm', PasswordType::class, [
                'label' => 'Подтверждение пароля',
                'attr' => [
                    'placeholder' => 'Подтверждение пароля',
                ]
            ])
            ->add('salt', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Защитный код',
                ]
            ])*/
            ->add('sex', ChoiceType::class, [
                'label' => 'Пол',
                'choices' => [
                    'Не указывать' => null,
                    'Мужской' => User::SEX_MALE,
                    'Женский' => User::SEX_FEMALE,
                ],
            ])
            ->add('new', SubmitType::class, [
                'label' => 'Готово',
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'validation_groups' => ['registration'],
        ]);
    }
}
