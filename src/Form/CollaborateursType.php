<?php

namespace App\Form;

use App\Entity\Collaborateurs;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CollaborateursType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('dateNaissance', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('dateEntree', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('dateSortie', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('statut', ChoiceType::class
                ,array('choices'  => ['Salarié'=>Collaborateurs::STAT_SALARIE, 'Stagiaire'=>Collaborateurs::STAT_STAGIAIRE,'Prestataire'=>Collaborateurs::STAT_PRESTATAIRE, 'Sorti'=>Collaborateurs::STAT_SORTI,], 'required' => true
                ))
            ->add('categorie', ChoiceType::class
                ,array('choices'  => ['NON CADRE'=>Collaborateurs::CAT_BFNONCADRE, 'CADRE'=>Collaborateurs::CAT_BFCADRE,], 'required' => true
                ))
            ->add('nbEnfant')
            ->add('nSecuriteSociale')
            ->add('mSalaireBase')
            ->add('mIndemLogement')
            ->add('mIndemTransport')
            ->add('mIndemFonction')
            ->add('mIndemAutres')
            ->add('mSecuriteSocialeSalarie')
            ->add('mSecuriteSocialePatronal')
            ->add('mImpotSalarie')
            ->add('mTaxePatronale')
            ->add('compteVirement')
            ->add('entreprise')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Collaborateurs::class,
        ]);
    }
}
