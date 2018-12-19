<?php

namespace App\Form;

use App\Entity\JourneeCaisses;
use App\Entity\TransfertInternationaux;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReceptionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mReceptionTrans', NumberType::class,array('grouping'=>3,'scale'=>0))
            ->add('transfertInternationaux', CollectionType::class, array(
                'entry_type' => ReceptionType::class,
                'allow_add'=>true,
                'allow_delete'=>true,
                'prototype' => true,
                'by_reference' => false,
                'attr' => ['class' => 'lignetransfert']
            ))



        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => JourneeCaisses::class,
        ]);
    }
    /*public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('mEmissionTrans')
            ->remove('transfertEmis')
        ;
    }

    public function getParent(){
        return TransfertType::class;
    }*/
}
