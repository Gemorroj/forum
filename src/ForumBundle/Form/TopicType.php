<?php

namespace ForumBundle\Form;

use ForumBundle\Entity\Post;
use ForumBundle\Entity\Topic;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class TopicType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('topic-title', TextType::class, ['label' => false, 'attr' => ['placeholder' => 'Заголовок']])
            ->add('post', PostType::class, ['label' => false, 'attr' => ['placeholder' => 'Сообщение']])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null
        ]);
    }
}
