<?php

namespace App\Form\Filter;

use App\Entity\Program;
use App\Filter\ReportFiler;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReportFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $currentDate = new \DateTime();

        $builder
            ->add('dateStart', DateType::class, [
                'label' => 'Fecha Inicio',
                'widget' => 'single_text',
                'html5' => true,
                'required' => true,
            ])
            ->add('dateEnd', DateType::class, [
                'label' => 'Fecha Fin',
                'widget' => 'single_text',
                'html5' => true,
                'required' => true,
                'attr' => [
                    'max' => $currentDate->format('Y-m-d')
                ]
            ])
            ->add('program', EntityType::class, [
                'class' => Program::class,            
                'label' => 'Programa',
                'required' => false,
                'multiple' => false,
                'expanded' => false
            ])
            ->add('save', SubmitType::class, [
               'label' => 'Generar'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ReportFiler::class,
        ]);
    }
}