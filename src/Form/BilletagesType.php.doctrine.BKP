<?php

namespace App\Form;

use App\Entity\BilletageLignes;
use App\Entity\Billetages;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BilletagesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('valeurTotal',NumberType::class,array('grouping'=>3,'scale'=>0, 'disabled'=>true))
            ->add('dateBillettage')
            ->add('billetageLignes', CollectionType::class, [
                'entry_type' => BilletageLignesType::class,
                'allow_add'=>true,
                'allow_delete'=>true,
                'prototype' => true,
                'by_reference' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Billetages::class,
        ]);
    }
}
