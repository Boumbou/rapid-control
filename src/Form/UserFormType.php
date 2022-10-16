<?php

namespace App\Form;

use App\Entity\User;
use Doctrine\DBAL\Types\StringType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, ["attr" => ["placeholder" => "email", "class" => "form-control"]])
            // ->add('roles')
            ->add('password', PasswordType::class, ["attr" => ["placeholder" => "mot de passe", "class" => "form-control"]])
            ->add('password_check', PasswordType::class, ["attr" => ["placeholder" => "vérification mot de passe", "class" => "form-control"]])
            ->add('submit', SubmitType::class, ["attr" => ["class" => "btn btn-primary "]]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
