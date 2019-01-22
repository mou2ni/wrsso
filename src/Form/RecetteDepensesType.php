<?php

namespace App\Form;

use App\Entity\RecetteDepenses;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecetteDepensesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateOperation', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('libelle')
            ->add('typeOperationComptable', EntityType::class, array (
                'class' => 'App\Entity\TypeOperationComptables',
                'choice_label' => 'libelle',
                'multiple' => false,
                'expanded'=>false,))
            ->add('mSaisie')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RecetteDepenses::class,
        ]);
    }
}
