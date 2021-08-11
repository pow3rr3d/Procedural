<?php

namespace App\Form;

use App\Entity\Process;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProcessType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Name')
            ->add('Description')
            ->add('CreatedAt')
            ->add('UpdatedAt')
            ->add('State')
            ->add('Step', CollectionType::class, [
                'entry_type' => StepType::class,
                'entry_options' => [
                    'label' => false,
                    'attr' => [
                        'class' => 'contacts',
                    ],
                ],
                'allow_add' => true,
                'allow_delete' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Process::class,
        ]);
    }
}
