<?php


namespace App\Form;


use App\Entity\Log;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditLogType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('description',TextareaType::class, [
            'required' => false,
            'label' => 'Meal',
        ]);

        $builder->add('eatenAt', DateTimeType::class, [
            'view_timezone' => 'America/Chicago',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Log::class,
            ]
        );
    }
}