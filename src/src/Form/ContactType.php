<?php

namespace App\Form;

use App\Entity\Company;
use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => "Name",
            ])
            ->add('surname', TextType::class, [
                'label' => "Surname",
            ])
            ->add('email', TextType::class, [
                'label' => "Email",
            ])
            ->add('mobile', TelType::class, [
                'required' => false,
                'label' => "Mobile",
            ])
            ->add('tel', TelType::class, [
                'required' => false,
                'label' => "Tel",
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
            'translation_domain' => 'company'
        ]);
    }

}