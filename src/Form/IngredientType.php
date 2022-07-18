<?php

namespace App\Form;

use App\Entity\Ingredient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class IngredientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'label_attr' => [
                    'class' => 'form-label mt-4',
                ],
                'attr' => [
                    'placeholder' => 'Nom de l\'ingrédient',
                    'class' => 'form-control',
                    'minlength' => 2,
                    'maxlength' => 50,
                ],
                'constraints' => [
                    new Assert\Length([
                        'min' => 2,
                        'max' => 50,
                        'minMessage' => 'Le nom de l\'ingrédient doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le nom de l\'ingrédient ne doit pas contenir plus de {{ limit }} caractères.',
                    ]),
                    new Assert\NotBlank([
                        'message' => 'Le nom de l\'ingrédient ne peut pas être vide.',
                    ]),
                ]
            ])
            ->add('price', MoneyType::class, [
                'label' => 'Prix',
                'label_attr' => [
                    'class' => 'form-label mt-4',
                ],
                'attr' => [
                    'placeholder' => 'Prix de l\'ingrédient',
                    'class' => 'form-control',
                    'min' => 0,
                    'max' => 200,
                ],
                'constraints' => [
                    new Assert\NotNull([
                        'message' => 'Le prix de l\'ingrédient ne peut pas être vide.',
                    ]),
                    new Assert\Positive([
                        'message' => 'Le prix de l\'ingrédient doit être positif.',
                    ]),
                    new Assert\LessThan([
                        'value' => 200,
                        'message' => 'Le prix de l\'ingrédient ne peut pas être supérieur à {{ compared_value }}.',
                    ]),
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Ajouter',
                'attr' => [
                    'class' => 'btn btn-primary btn-block mt-4',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ingredient::class,
        ]);
    }
}
