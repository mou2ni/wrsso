<?php

namespace App\Form;

use App\Entity\JournauxComptables;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JournauxComptablesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code')
            ->add('typeJournal', ChoiceType::class
                ,array('choices'  => ['Trésorerie'=>JournauxComptables::TYP_TRESORERIE, 'Achats'=>JournauxComptables::TYP_ACHAT, 'Ventes'=>JournauxComptables::TYP_VENTE, 'OD'=>JournauxComptables::TYP_OD], 'required' => true
                ))
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
