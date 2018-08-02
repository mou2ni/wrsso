<?php

namespace App\Form;

use App\Entity\JourneeCaisses;
use App\Entity\TransfertInternationaux;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransfertType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mReceptionTrans')
            ->add('mEmissionTrans')
            ->add('transfertInternationaux', CollectionType::class, array(
                'entry_type' => TransfertInternationauxType::class,
                'allow_add'=>true,
                'allow_delete'=>true,
                'prototype' => true,
                'by_reference' => false
            ))


        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => JourneeCaisses::class,
        ]);
    }
}