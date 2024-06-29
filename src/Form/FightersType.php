<?php

namespace App\Form;

use App\Entity\Fighters;
use App\Entity\Sides;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;

class FightersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'label_attr' => [
                    'class' => 'form_label',
                ],
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Le nom doit contenir au moins {{ limit }} caractères',
                        'max' => 20,
                        'maxMessage' => 'Le nom doit contenir au maximum {{ limit }} caractères',
                    ]),
                ],
                'attr' => [
                    'placeholder' => 'Indiquer le nom du personnage',
                    'class' => 'form_input',
                ],
            ])
            ->add('image', FileType::class, [
                'label' => 'Avatar',
                'mapped' => false, // ne sera pas lié à l'entité 'Fighters'
                'label_attr' => [
                    'class' => 'form_label',
                ],
                'attr' => [
                    'class' => 'form_input text-neutral-100',
                ],
                'constraints' => [
                    new Image([
                        'maxSize' => '500k',
                        'mimeTypes' => [
                            'image/jpg',
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                        ],
                        'mimeTypesMessage' => "Ceci n'est pas une image ! (jpg, jpeg, png, gif)",
                        "maxSizeMessage" => "L'image est trop lourde ({{ size }} {{ suffix }}). La taille maximale autorisée est de {{ limit }} {{ suffix }}.",
                    ]),
                ]
            ])
            ->add('health', NumberType::class, [
                'label' => 'Santé',
                'label_attr' => [
                    'class' => 'form_label',
                ],
                'attr' => [
                    'placeholder' => 'Indiquer les points de vie du personnage',
                    'class' => 'form_input',
                ],
            ])
            ->add('magic', NumberType::class, [
                'label' => 'Magie',
                'label_attr' => [
                    'class' => 'form_label',
                ],
                'attr' => [
                    'placeholder' => 'Indiquer les points de mana du personnage',
                    'class' => 'form_input',
                ],
            ])
            ->add('power', NumberType::class, [
                'label' => 'Puissance',
                'label_attr' => [
                    'class' => 'form_label',
                ],
                'attr' => [
                    'placeholder' => 'Indiquer les points d\'attaque du personnage',
                    'class' => 'form_input',
                ],
            ])
            ->add('side', EntityType::class, [
                'required' => true,
                'class' => Sides::class, // entité en lien avec le champ
                'label' => 'Choisissez le côté du combattant',
                'label_attr' => [
                    'class' => 'form_label',
                ],
                'expanded' => false,  // affiche les adresses sous forme de radio plutot qu'en Select
                'choices' => $options['sides'], // les cotés à afficher
                'attr' => [
                    'class' => 'form_input',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Créer ce nouveau personnage",
                'attr' => [
                    'class' => 'btn_form',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Fighters::class,
            'sides' => null,
        ]);
    }
}
