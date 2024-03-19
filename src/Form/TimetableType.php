<?php

namespace App\Form;

use App\Entity\PersonInProgram;
use App\Entity\Timetable;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TimetableType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $currentDate = new \DateTime();

        $entity = $builder->getData();

        if (null !== $entity->getDateStart()) {
            $builder
                ->add('dateEnd',  DateType::class, [
                    'label' => 'Fecha Finalización',
                    'widget' => 'single_text',
                    'html5' => true,
                    'required' => true,
                    'attr' => [
                        'max' => $currentDate->format('Y-m-d')
                    ]
                ]);
        } else {
            $builder
                ->add('dateStart',  DateType::class, [
                    'label' => 'Fecha Inicio',
                    'widget' => 'single_text',
                    'html5' => true,
                    'required' => true,
                    'attr' => [
                        'min' => $currentDate->format('Y-m-d')
                    ]
                ])
            ;
        }

        $builder
            ->add('save', SubmitType::class, [
                'label' => 'Guardar'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Timetable::class,
        ]);
    }
}
