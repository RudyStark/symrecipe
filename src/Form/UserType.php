<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullName', TextType::class, [
                'label' => 'Nom / Prénom',
                'label_attr' => [
                    'class' => 'col-sm-2 col-form-label',
                ],
                'attr' => [
                    'class' => 'form-control',
                    'minlength' => '2',
                    'maxlength' => '50',
                ],
                'constraints' => [
                    new Assert\Length([
                        'min' => '2',
                        'max' => '50',
                        'minMessage' => 'Le nom doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le nom ne doit pas contenir plus de {{ limit }} caractères.',
                    ]),
                    new Assert\NotBlank([
                        'message' => 'Le nom ne peut pas être vide.',
                    ]),
                ],
            ])
            ->add('pseudo', TextType::class, [
                'label' => 'Pseudo (facultatif)',
                'label_attr' => [
                    'class' => 'col-sm-2 col-form-label',
                ],
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'minlength' => '2',
                    'maxlength' => '50',
                ],
                'constraints' => [
                    new Assert\Length([
                        'min' => '2',
                        'max' => '50',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                'label' => 'Mot de passe',
                'label_attr' => [
                    'class' => 'col-sm-2 col-form-label',
                ],
                'attr' => [
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Le mot de passe ne peut pas être vide.',
                    ]),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Modifier',
                'attr' => [
                    'class' => 'btn btn-primary mt-4',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
