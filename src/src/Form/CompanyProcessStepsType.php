<?php

namespace App\Form;

use App\Entity\Company;
use App\Entity\CompanyProcessStep;
use App\Entity\Step;
use Doctrine\DBAL\Types\BooleanType;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompanyProcessStepsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('step', CheckboxType::class, [
                'required' => false,
                'label' => "<p> <b>" . $builder->getData()->getTitle() . "</b> <br> " . $builder->getData()->getDescription() . "</p>",
                'label_html' => true,
                'mapped' => false,
                'help' => "Not required - " . $builder->getData()->getHelper(),
                'help_html' => true
            ]);

        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
            if ($event->getData()->getIsRequired() === true) {
                $event->getForm()->add("step", CheckboxType::class, [
                    'required' => true,
                    'label' => "<p> <b>" . $event->getData()->getTitle() . "</b> <br> " . $event->getData()->getDescription() . "</p>",
                    'label_html' => true,
                    'mapped' => false,
                    'help' => "Required - " .$event->getData()->getHelper(),
                    'help_html' => true
                ]);
            }
        });

    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Step::class,
            ]
        );
    }
}
