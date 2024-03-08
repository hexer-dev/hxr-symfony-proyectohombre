<?php

namespace App\Form;

use App\Entity\Headquarter;
use App\Entity\Program;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProgramType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code', TextType::class, [
                'label' => 'Código',
                'required' => true
            ])
            ->add('name', TextType::class, [
                'label' => 'Nombre',
                'required' => true
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Descripción',
                'attr' => ['rows' => '3'],

            ])
            ->add('date_start', DateType::class, [
                'label' => 'Fecha Inicio',
                'html5' => false,
                'format' => 'dd/MM/yyyy',
                'required' => true,
            ])
            ->add('date_end', DateType::class, [
                'label' => 'Fecha Fin',
                'html5' => false,
                'format' => 'dd/MM/yyyy',
                'required' => true,
            ])
            ->add('headquarter', EntityType::class, [
                'class' => Headquarter::class,
                'label' => 'Sedes',
                'required' => true,
                'multiple' => false,
                'expanded' => false
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Guardar'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Program::class,
        ]);
    }
}
