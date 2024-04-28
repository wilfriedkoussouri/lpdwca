<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Expression;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                "label" => 'Email address',
                "label_attr" => ['class' => "block text-sm font-medium leading-6 text-gray-900"],
                'attr' => ['class' => 'block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6'],
            ])
            ->add('user_name', TextType::class, [
                "label" => 'Username',
                "label_attr" => ['class' => "block text-sm font-medium leading-6 text-gray-900"],
                'attr' => ['class' => 'block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6']

            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options'  => [
                    'label' => 'Password',
                    "label_attr" => ['class' => "block text-sm font-medium leading-6 text-gray-900"],
                    'attr' => ['autocomplete' => 'new-password', "class" => "block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"],
                    'hash_property_path' => 'password'
                ],
                'second_options' => [
                    'label' => 'Confirm Password',
                    "label_attr" => ['class' => "block text-sm font-medium leading-6 text-gray-900"],
                    'attr' => ['autocomplete' => 'new-password', "class" => "block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"],
                ],
                'mapped' => false,
            ])
            ->add('RGPDConsent', CheckboxType::class, [
                "label" => "RGPD Consent",
                "label_attr" => ['class' => "font-medium text-gray-900"],
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
                "attr" => ["class" => "h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600"],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
