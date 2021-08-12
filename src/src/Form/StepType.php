<?php

namespace App\Form;

use App\Entity\Process;
use App\Entity\Step;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StepType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Title')
            ->add('Description', TextareaType::class, [
                "attr" => [
                    "maxlength" => "255"
                ]
            ])
            ->add('Helper', TextareaType::class, [
                "attr" => [
                    "maxlength" => "255"
                ]
            ])
            ->add('IsRequired')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Step::class,
        ]);
    }
}
