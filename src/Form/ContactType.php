<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullName', TextType::class, [
                'label' => 'Nom / Prénom',
                'label_attr' => [
                    'class' => 'mt-4 col-form-label',
                ],
                'attr' => [
                    'class' => 'form-control',
                    'minlength' => '2',
                    'maxlength' => '50',
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Adresse email',
                'label_attr' => [
                    'class' => 'mt-4 col-form-label',
                ],
                'attr' => [
                    'class' => 'form-control',
                    'minlength' => '2',
                    'maxlength' => '180',
                ],
                'constraints' => [
                    new Assert\Email([
                        'message' => 'L\'adresse email n\'est pas valide.',
                    ]),
                    new Assert\Length([
                        'min' => '2',
                        'max' => '180',
                    ]),
                    new Assert\NotBlank([
                        'message' => 'L\'email ne peut pas être vide.',
                    ]),
                ],
            ])
            ->add('subject', TextType::class, [
                'label' => 'Objet',
                'label_attr' => [
                    'class' => 'mt-4 col-form-label',
                ],
                'attr' => [
                    'class' => 'form-control',
                    'minlength' => '2',
                    'maxlength' => '300',
                ],
                'constraints' => [new Assert\Length([
                    'min' => '2',
                    'max' => '300',
                ]),]
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Message',
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
            ->add('submit', SubmitType::class, [
                'label' => 'Soumettre ma demande',
                'attr' => [
                    'class' => 'btn btn-primary mt-4',
                ],
            ])
            ->add('captcha', Recaptcha3Type::class, [
                'constraints' => new Recaptcha3(),
                'action_name' => 'contact',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
