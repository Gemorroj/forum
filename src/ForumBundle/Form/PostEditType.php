<?php

namespace ForumBundle\Form;

use ForumBundle\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostEditType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('text', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Сообщение',
                ]
            ])
            ->add('cancel', ButtonType::class, [
                'label' => 'Отмена',
                'attr' => [
                    'data-rel' => 'back',
                ],
            ])->add('edit', SubmitType::class, [
                'label' => 'Готово',
                'attr' => [
                    'data-rel' => 'back',
                    'data-transition' => 'flow',
                ],
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
            'attr' => [
                'action' => '',
                'id' => 'post_edit_form',
            ],
        ]);
    }
}
