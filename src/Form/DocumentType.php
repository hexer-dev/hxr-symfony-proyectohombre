<?php

namespace App\Form;

use App\Entity\Document;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DocumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nombre',
                'required' => true
            ])
            ->add('typeDocument', ChoiceType::class, [
                'label' => '¿Tipo documento a subir?',
                'choices' => [
                    'Archivo' => 'file',
                    'Enlace' => 'link',
                ],
                'required' => true,
                'expanded' => true,
                'multiple' => false,
                'mapped' => false                
            ])
            ->add('file', FileType::class, [
                'label' => 'Archivo',
                'required' => false,
                'mapped' => false
            ])
            ->add('link', UrlType::class, [
                'label' => 'Enlace',
                'required' => false,
                'help' => 'Introduzca la url completa, con https:\\'
            ])
            ->add('year', IntegerType::class, [
                'label' => 'Año',
                'required' => false
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Guardar'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Document::class,
        ]);
    }
}
