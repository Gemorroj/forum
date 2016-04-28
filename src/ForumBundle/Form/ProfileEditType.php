<?php

namespace ForumBundle\Form;

use ForumBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileEditType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('plainPassword', PasswordType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Пароль',
                ]
            ])
            ->add('sex', ChoiceType::class, [
                'required' => false,
                'label' => false,
                'choices' => [
                    'Не указывать' => null,
                    'Мужской' => User::SEX_MALE,
                    'Женский' => User::SEX_FEMALE,
                ],
            ])
            ->add('edit', SubmitType::class, [
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
            'validation_groups' => ['profile_edit'],
        ]);
    }
}
