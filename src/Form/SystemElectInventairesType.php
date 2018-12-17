<?php

namespace App\Form;

use App\Entity\SystemElectInventaires;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SystemElectInventairesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateInventaire')
            ->add('soldeTotal', NumberType::class,array('grouping'=>3,'scale'=>0, 'disabled'=>true))
            ->add('systemElectLigneInventaires', CollectionType::class, [
                'entry_type' => SystemElectLigneInventairesType::class,
                'allow_add'=>true,
                'allow_delete'=>true,
                'prototype' => true,
                'by_reference' => false
            ])
            //->add('journeeCaisse')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SystemElectInventaires::class,
        ]);
    }
}
