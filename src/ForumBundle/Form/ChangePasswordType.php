<?php

namespace ForumBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

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
                'constraints' => new UserPassword([
                    'message' => 'Введите действующий пароль!',
                ]),
            ])
            ->add('plainPassword', RepeatedType::class, [
                'required' => true,
                'first_options'  => ['label' => 'Новый пароль'],
                'second_options' => ['label' => 'Повторить новый пароль'],
                'type' => PasswordType::class,
                'invalid_message' => 'Новые пароли должны совпадать!',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Введите новый пароль!',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Новый пароль должен содержать не менее {{ limit }} символа(ов)',
                    ]),
                ],
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
            'data_class' => null,
            'validation_groups' => ['Default'],
        ]);
    }
}
