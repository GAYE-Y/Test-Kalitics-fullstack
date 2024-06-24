<?php

namespace App\Form;

use App\Entity\Clocking;
use App\Entity\Project;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateMultiClockingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('clockingProject', EntityType::class, [
            'class'        => Project::class,
            'choice_label' => 'name',
            'label'        => 'entity.Clocking.clockingProject',
        ]);
        $builder->add('clockingUsers', EntityType::class, [
            'class'        => User::class,
            'choice_label' => static function (?User $choice): ?string {
                return $choice === null
                    ? null
                    : $choice->getLastName() . ' ' . $choice->getFirstName();
            },
            'multiple'     => true,
            'expanded'     => true,
            'label'        => 'entity.Clocking.clockingUsers',
        ]);
        $builder->add('date', DateType::class, [
            'label' => 'entity.Clocking.dateEnd',
        ]);
        $builder->add('duration', IntegerType::class, [
            'label' => 'entity.Clocking.duration',
        ]);
        $builder->add('submit', SubmitType::class, [
            'label' => 'Créer',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Clocking::class,
        ]);
    }
}
