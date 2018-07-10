<?php

namespace App\Form;

use App\Entity\DetteCreditDivers;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DetteCreditDiversType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateDC')
            ->add('libelle')
            ->add('statut')
            ->add('mCredit')
            ->add('mDette')
            ->add('idCaisse')
            ->add('idUtilisateurCreation')
            ->add('idUtilisateurRemb')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DetteCreditDivers::class,
        ]);
    }
}