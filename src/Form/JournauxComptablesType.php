<?php

namespace App\Form;

use App\Entity\JournauxComptables;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JournauxComptablesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code')
            ->add('typeJournal')
            ->add('libelle')
            ->add('dernierNumPiece')
            ->add('compteContrePartie')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => JournauxComptables::class,
        ]);
    }
}
