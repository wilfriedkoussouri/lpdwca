<?php

// src/Form/ContactType.php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => 50]),
                ],
                'label' => 'First name',
                'attr' => ['class' => 'block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm'],
            ])
            ->add('lastName', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => 50]),
                ],
                'label' => 'Last name',
                'attr' => ['class' => 'block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm'],
            ])
            ->add('company', TextType::class, [
                'constraints' => [
                    new Assert\Length(['max' => 100]),
                ],
                'label' => 'Company',
                'required' => false,
                'attr' => ['class' => 'block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm'],
            ])
            ->add('email', EmailType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Email(),
                ],
                'label' => 'Email',
                'attr' => ['class' => 'block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm'],
            ])
            ->add('phoneNumber', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => 20]),
                ],
                'label' => 'Phone number',
                'attr' => ['class' => 'block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm'],
            ])
            ->add('message', TextareaType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => 1000]),
                ],
                'label' => 'Message',
                'attr' => ['class' => 'block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([]);
    }
}
