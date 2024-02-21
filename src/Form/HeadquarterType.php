<?php

namespace App\Form;

use App\Entity\Headquarter;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HeadquarterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nombre',
                'required' => true
            ])
            ->add('phone', TextType::class, [
                'label' => 'Teléfono',
                'required' => false
            ])
            ->add('address', TextType::class, [
                'label' => 'Dirección',
                'required' => false
            ])
            ->add('users', EntityType::class, [
                'class' => User::class,
                'label' => 'Profesionales',
                'query_builder' => function (EntityRepository $er) {
                    return $er->onlyManagerAndConsultant();
                },
                'required' => true,
                'multiple' => true,
                'expanded' => false
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Guardar'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Headquarter::class,
        ]);
    }
}
