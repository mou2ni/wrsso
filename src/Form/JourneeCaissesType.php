<?php

namespace App\Form;

use App\Entity\JourneeCaisses;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JourneeCaissesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('caisse')
            ->add('billetOuv')
            ->add('systemElectInventOuv')
            ->add('ecartOuv')
            ->add('billetFerm')
            ->add('systemElectInventFerm')
            ->add('detteCreditCreations')
            ->add('detteCreditRembs')
            ->add('intercaisseEntrants')
            ->add('intercaisseSortants')
            ->add('transfertInternationaux')
            ->add('deviseJournees')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => JourneeCaisses::class,
        ]);
    }
}
