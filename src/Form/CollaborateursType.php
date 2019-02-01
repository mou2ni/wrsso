<?php

namespace App\Form;

use App\Entity\Collaborateurs;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CollaborateursType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('dateNaissance')
            ->add('dateEntree')
            ->add('dateSortie')
            ->add('statut')
            ->add('nbEnfant')
            ->add('categorie')
            ->add('nSecuriteSociale')
            ->add('mSalaireBase')
            ->add('mIndemLogement')
            ->add('mIndemTransport')
            ->add('mIndemFonction')
            ->add('mIndemAutres')
            ->add('mSecuriteSocialeSalarie')
            ->add('mSecuriteSocialePatronale')
            ->add('mImpotSalarie')
            ->add('mTaxePatronale')
            ->add('compteVirement')
            ->add('entreprise')
            ->add('dernierSalaire')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Collaborateurs::class,
        ]);
    }
}
