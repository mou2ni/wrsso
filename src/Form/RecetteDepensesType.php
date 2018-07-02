<?php

namespace App\Form;

use App\Entity\RecetteDepenses;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecetteDepensesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateOperation')
            ->add('mRecette')
            ->add('libelle')
            ->add('mDepense')
            ->add('statut')
            ->add('idUtilisateur')
            ->add('idTrans')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RecetteDepenses::class,
        ]);
    }
}
