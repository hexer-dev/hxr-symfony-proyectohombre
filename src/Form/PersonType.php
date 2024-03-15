<?php

namespace App\Form;

use App\Entity\Drug;
use App\Entity\Headquarter;
use App\Entity\Person;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PersonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $entity = $builder->getData();

        $dataTypeValue = (null === $entity->getId()) ? 'BENEFICIARY' : $entity->getType();

        $currentDate = new \DateTime();

        $builder
            ->add('nif', TextType::class, [
                'label' => 'NIF',
                'required' => true
            ])
            ->add('name', TextType::class, [
                'label' => 'Nombre',
                'required' => true
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Apellidos',
                'required' => true
            ])
            ->add('birthdate', BirthdayType::class, [
                'label' => 'Fecha Nacmiento',
                'widget' => 'single_text',
                'html5' => true,
                'required' => false,
                'attr' => [
                    'max' => $currentDate->format('Y-m-d')
                ]
            ])
            ->add('gender', ChoiceType::class, [
                'label' => 'Sexo',
                'choices' => array_flip(Person::GENDER),
                'required' => false
            ])
            ->add('phone', TextType::class, [
                'label' => 'Teléfono',
                'required' => false
            ])
            ->add('address', TextType::class, [
                'label' => 'Dirección',
                'required' => false
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Tipo',
                'choices' => array_flip(Person::TYPE),
                'required' => true,
                'data' => $dataTypeValue
            ])
            ->add('nationality', TextType::class, [
                'label' => 'Nacionalidad',
                'required' => false
            ])
            ->add('homeless', CheckboxType::class, [
                'label' => 'Sin techo',
                'required' => false
            ])
            ->add('drug', EntityType::class, [
                'class' => Drug::class,
                'label' => 'Adicción principal',
                'required' => true,
                'multiple' => false,
                'expanded' => false
            ])
            ->add('contactName', TextType::class, [
                'label' => 'Persona Contacto',
                'required' => false
            ])
            ->add('contactPhone', TextType::class, [
                'label' => 'Teléfono Persona Contacto',
                'required' => false
            ]);       

        if (null !== $entity->getId()) {
            $headquarter = $options['headquarter'];

            $builder
                ->add('createdBy', EntityType::class, [
                    'class' => User::class,
                    'query_builder' => function (EntityRepository $er) use ($headquarter){
                        return $er->managerOfHeadquarter($headquarter);
                    },
                    'label' => 'Profesional Referencia',
                    'required' => true,
                    'multiple' => false,
                    'expanded' => false
                ]);
        }

        $builder
            ->add('save', SubmitType::class, [
                'label' => 'Guardar'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Person::class,
            'headquarter'=> null
        ]);
    }
}
