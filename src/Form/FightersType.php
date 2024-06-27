<?php

namespace App\Form;

use App\Entity\Fighters;
use App\Entity\sides;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FightersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('image')
            ->add('health')
            ->add('magic')
            ->add('power')
            ->add('side', EntityType::class, [
                'class' => sides::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Fighters::class,
        ]);
    }
}
