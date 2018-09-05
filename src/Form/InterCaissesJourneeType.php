<?php

namespace App\Form;

use App\Entity\InterCaisses;
use App\Entity\JourneeCaisses;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InterCaissesJourneeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('intercaisseEntrants', CollectionType::class, array (
                'entry_type' => InterCaissesType::class,
                'entry_options' => array('label' => false),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => false,
            ))
            //->add('intercaisseSortants')

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => JourneeCaisses::class,
        ]);
    }
}

/*class InterCaissesJourneeType extends AbstractType
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
*/