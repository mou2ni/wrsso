<?php

namespace App\Form;

use App\Entity\InterCaisses;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InterCaissesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mIntercaisse')
            ->add('statut')
            ->add('observations')
            ->add('idJourneeCaisseSource')
            ->add('idJourneeCaisseDestination')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => InterCaisses::class,
        ]);
    }
}
