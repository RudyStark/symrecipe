<?php

namespace App\Form;

use App\Entity\Recipe;
use App\Entity\Ingredient;
use App\Repository\IngredientRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RecipeType extends AbstractType
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
                    'class' => 'form-control',
                    'minlength' => 2,
                    'maxlength' => 50,
                ],
                'constraints' => [
                    new Assert\Length([
                        'min' => 2,
                        'max' => 50,
                        'minMessage' => 'Le nom de la recette doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le nom de la recette ne doit pas contenir plus de {{ limit }} caractères.',
                    ]),
                    new Assert\NotBlank([
                        'message' => 'Le nom de la recette ne peut pas être vide.',
                    ]),
                ]
            ])
            ->add('time', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'min' => 1,
                    'max' => 1140,
                ],
                'required' => false,
                'label' => 'Temps de cuisson (en minutes)',
                'label_attr' => [
                    'class' => 'form-label mt-4',
                ],
                'constraints' => [
                    new Assert\Positive([
                        'message' => 'Le temps de cuisson doit être positif.',
                    ]),
                    new Assert\LessThan([
                        'value' => 1140,
                        'message' => 'Le temps de cuisson ne peut pas être supérieur à {{ compared_value }}.',
                    ]),
                ]
            ])
            ->add('nbPeople', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'min' => 1,
                    'max' => 50,
                ],
                'required' => false,
                'label' => 'Nombre de personnes',
                'label_attr' => [
                    'class' => 'form-label mt-4',
                ],
                'constraints' => [
                    new Assert\Positive([
                        'message' => 'le nombre de personnes doit être positif.',
                    ]),
                    new Assert\LessThan([
                        'value' => 51,
                        'message' => 'Le nombe de personnes ne peut pas être supérieur à {{ compared_value }}.',
                    ]),
                ]
            ])
            ->add('difficulty', RangeType::class, [
                'attr' => [
                    'class' => 'form-range',
                    'min' => 1,
                    'max' => 5,
                ],
                'required' => false,
                'label' => 'Difficulté',
                'label_attr' => [
                    'class' => 'form-label mt-4',
                ],
                'constraints' => [
                    new Assert\Positive(),
                    new Assert\LessThan([
                        'value' => 5,
                    ]),
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'label_attr' => [
                    'class' => 'form-label mt-4',
                ],
                'attr' => [
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'La description de la recette ne peut pas être vide.',
                    ]),
                ]
            ])
            ->add('price', MoneyType::class, [
                'label' => 'Prix',
                'label_attr' => [
                    'class' => 'form-label mt-4',
                ],
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'min' => 0,
                    'max' => 200,
                ],
                'constraints' => [
                    new Assert\Positive(),
                    new Assert\LessThan([
                        'value' => 1001,
                    ]),
                ]
            ])
            ->add('isFavorite', CheckboxType::class, [
                'label' => 'Favoris ?',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'required' => false,
                'attr' => [
                    'class' => 'form-check-input',
                ],
                'constraints' => [
                    new Assert\NotNull(),
                ]
            ])
            ->add('ingredients', EntityType::class, [
                'label' => 'Les ingrédients',
                'label_attr' => [
                    'class' => 'form-label mt-4',
                ],
                'attr' => [
                    'class' => 'form-control d-flex flex-column',
                ],
                'query_builder' => function (IngredientRepository $repository) {
                    return $repository->createQueryBuilder('i')
                        ->orderBy('i.name', 'ASC');
                },
                'class' => Ingredient::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Confirmer',
                'attr' => [
                    'class' => 'btn btn-primary btn-block mt-4',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
