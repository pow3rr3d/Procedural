<?php

namespace App\Form;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use mysql_xdevapi\Exception;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;

class UserType extends AbstractType
{
    private $user;
    private $security;
    private $em;
    private $encoder;
    private $router;

    public function __construct(Security $security, EntityManagerInterface $em,UserPasswordEncoderInterface $encoder, RouterInterface $router)
    {
        $this->security = $security;
        $this->user = $this->security->getUser();
        $this->em = $em;
        $this->encoder = $encoder;
        $this->router = $router;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Name')
            ->add('Surname')
            ->add('Email')
            ->add('Password', PasswordType::class, [
        "required" => false,
    ]);

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $user = $event->getData();
            $current = $this->em->getRepository(User::class)->findOneBy(["Email" => $user['Email']]);


            if ($current !== null){
                if (empty($user["Password"])) {
                    $user["Password"] = $this->em->getRepository(User::class)->findOneBy(["Email" => $user['Email']])->getPassword();
                }
                else{
                    $user["Password"] = $this->encoder->encodePassword($this->em->getRepository(User::class)->findOneBy(["Email" => $user['Email']]), $user["Password"]);
                }
            }

            $event->setData($user);
        });
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
