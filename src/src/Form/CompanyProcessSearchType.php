<?php

namespace App\Form;

use App\Entity\Company;
use App\Entity\CompanyProcess;
use App\Entity\CompanyProcessStep;
use App\Entity\Process;
use App\Entity\State;
use App\Entity\Step;
use Doctrine\DBAL\Types\BooleanType;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompanyProcessSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Company', EntityType::class, [
                'class' => Company::class,
                'choice_label' => 'Name',
                'multiple' => false,
                'required' => false
            ])
            ->add('Process', EntityType::class, [
                'class' => Process::class,
                'choice_label' => 'Name',
                'multiple' => false,
                'required' => false
            ])
            ->add('State', EntityType::class, [
                'class' => State::class,
                'choice_label' => 'Name',
                'multiple' => false,
                'required' => false
            ]);
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => CompanyProcess::class,
            ]
        );
    }
}
