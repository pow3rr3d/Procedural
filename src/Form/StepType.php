<?php

namespace App\Form;

use App\Entity\Process;
use App\Entity\Step;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StepType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description', TextareaType::class, [
                "attr" => [
                    "maxlength" => "255"
                ]
            ])
            ->add('helper', TextareaType::class, [
                "attr" => [
                    "maxlength" => "255"
                ]
            ])
            ->add('isRequired')
            ->add('weight', NumberType::class, [
                'label'=> false,
                'attr' => [
                    'class' => 'weight',
                    'style' => 'display: none'
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Step::class,
        ]);
    }
}
