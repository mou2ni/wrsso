<?php

namespace App\Form;

use App\Entity\DepotRetraits;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DepotRetraitsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateOperation')
            ->add('libelle')
            ->add('mDepot')
            ->add('mRetrait')
            ->add('statut')
            ->add('utilisateur')
            ->add('transaction')
            ->add('compteOperationCaisse')
            ->add('compteClient')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DepotRetraits::class,
        ]);
    }
}
