<?php

namespace App\Form;

use App\Entity\Person;
use App\Entity\PersonInProgram;
use App\Entity\Program;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PersonInProgramType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $entity = $builder->getData();

        $headquarter = $options['headquarter'];

        $urlReturn = $options['urlReturn'];

        $builder
            ->add('reductIrpf', CheckboxType::class, [
                'label' => '¿Reducción de IRPF?',
                'required' => false
            ]);

        if (null === $entity->getPerson()) {
            $program = $entity->getProgram();

            $builder
                ->add('person', EntityType::class, [
                    'class' => Person::class,
                    'query_builder' => function (EntityRepository $er) use ($headquarter, $program) {
                        return $er->personInHeadquarterWithoutProgram($headquarter, $program);
                    },
                    'label' => 'Persona Beneficiaria',
                    'required' => true,
                    'multiple' => false,
                    'expanded' => false
                ]);
        }

        if (null === $entity->getProgram()) {

            $builder
                ->add('program', EntityType::class, [
                    'class' => Program::class,
                    'query_builder' => function (EntityRepository $er) use ($headquarter) {
                        return $er->managerProgramsQuery($headquarter);
                    },
                    'label' => 'Programa',
                    'required' => true,
                    'multiple' => false,
                    'expanded' => false
                ]);
        }

        if (null !== $urlReturn) {
            $builder
                ->add('urlReturn', HiddenType::class, [
                    'data' => $urlReturn,
                    'mapped' => false
                ]);
        }

        $builder
            ->add('save', SubmitType::class, [
                'label' => 'Guardar'
            ]);;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PersonInProgram::class,
            'headquarter' => null,
            'urlReturn' => null
        ]);
    }
}
