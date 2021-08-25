<?php


namespace App\Form;


use App\DataStructure\TrackedFoodDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrackedFoodType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('description', TextType::class, [
            'required' => true,
        ]);

        $builder->add('timeIntervalCount', IntegerType::class, [
            'required' => true,
        ]);

        $builder->add('timeIntervalType', ChoiceType::class, [
            'required' => true,
            'expanded' => true,
            'multiple' => false,
            'choices' => [
                'Day(s)' => 'day',
                'Week(s)' => 'week',
//                'Month(s)' => 'month',
            ],
            'help' => 'If you want to do once a month, try something like 30 days or 4 weeks.'
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => TrackedFoodDto::class,
            ]
        );
    }

}