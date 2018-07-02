<?php

namespace App\Form;

use App\Entity\DeviseIntercaisses;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeviseIntercaissesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mIntercaisse')
            ->add('statut')
            ->add('observations')
            ->add('idJourneeCaisseSource')
            ->add('idJourneeCaissePartenaire')
            ->add('idDevise')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DeviseIntercaisses::class,
        ]);
    }
}
