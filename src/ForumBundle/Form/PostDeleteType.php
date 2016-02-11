<?php

namespace ForumBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use ForumBundle\Entity\Forum;

class PostDeleteType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cancel', ButtonType::class, [
                'attr' => [
                    'data-rel' => 'back',
                ],
            ])->add('delete', SubmitType::class, [
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
            'data_class' => Forum::class,
            'attr' => [
                'action' => '',
                'id' => 'post_delete_form',
            ],
        ]);
    }
}
