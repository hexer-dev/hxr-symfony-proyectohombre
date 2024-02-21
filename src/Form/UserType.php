<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $roles = [
            'Administrador' => 'ROLE_ADMIN',
            'Gestores' => 'ROLE_MANAGER',
            'Consultor' => 'ROLE_CONSULTANT',
        ];

        $builder
            ->add('name', TextType::class, [
                'label' => 'Nombre',
                'required' => true
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Apellidos',
                'required' => true
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => true
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'Roles',
                'choices'  => $roles,
                'multiple' => true,
                'expanded' => false,
                'required' => true
            ]);

        $entity = $builder->getData();

        $builder
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => (null === $entity->getId()),
                'first_options'  => [
                    'label' => 'Contraseña',
                    'help' => (null == $entity->getId())
                        ? ''
                        : 'Deja los campos de contraseña vacíos para mantener la contraseña actual.'
                ],
                'second_options' => [
                    'label' => 'Repetir Contraseña'
                ],
                'invalid_message' => 'Las contraseñas no coinciden.',
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Guardar'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
