<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', RepeatedType::class, [
            'type' => EmailType::class,
            'first_options' => [
                'label' => 'Email',
                'required'=> true,
            ],
            'second_options' => [
                'label' => 'Repeat Email',
                'required'=> true,
            ],
        ]);
        $builder->add('plainPassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'mapped' => false,
            'first_options' => [
                'label' => 'Password',
                'attr' => ['autocomplete' => 'new-password'],
                'required'=> true,
            ],
            'second_options' => [
                'label' => 'Repeat Password',
                'attr' => ['autocomplete' => 'new-password'],
                'required'=> true,
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
                                   'data_class' => User::class,
                               ]);
    }
}
