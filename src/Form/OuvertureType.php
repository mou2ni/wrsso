<?php

namespace App\Form;

use App\Entity\JourneeCaisses;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OuvertureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('billetFerm')
            ->remove('systemElectInventFerm')
            ->remove('detteCreditCreations')
            ->remove('detteCreditRembs')
            ->remove('intercaisseEntrants')
            ->remove('intercaisseSortants')
            ->remove('transfertInternationaux')
        ;
    }

    public function getParent(){
        return JourneeCaisses::class;
    }
}
