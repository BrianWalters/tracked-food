<?php


namespace App\Form;


use App\DataStructure\LogDto;
use App\Entity\TrackedFood;
use App\Entity\User;
use App\Repository\TrackedFoodRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewLogType extends AbstractType
{
    public function __construct(public TrackedFoodRepository $trackedFoodRepository)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $options['user'];
        if (!$user instanceof User)
            throw new \LogicException('Wrong user class.');

        $builder->add('trackedFood', EntityType::class, [
            'label' => 'Food',
            'required' => true,
            'class' => TrackedFood::class,
            'choice_label' => 'description',
            'query_builder' => $this->trackedFoodRepository->getUserQueryBuilder($user),
            'expanded' => false,
            'multiple' => false,
        ]);

        $builder->add('description',TextareaType::class, [
            'required' => false,
            'label' => 'Meal',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => LogDto::class,
            ]
        );
        $resolver->setRequired('user');
    }

}