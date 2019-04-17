<?php

namespace App\Form;

use App\Entity\Agences;
use App\Entity\Collaborateurs;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
            ->add('agence', EntityType::class, array (
                'class' => 'App\Entity\Agences',
                'choice_label' => function (Agences $agence) {
                    return $agence->getDisplayName();},
                'multiple' => false,
                'expanded'=>false,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('a')
                        ->orderBy('a.code', 'ASC');
                }))
            ->add('statut', ChoiceType::class
                ,array('choices'  => ['Salarié'=>Collaborateurs::STAT_SALARIE, 'Stagiaire'=>Collaborateurs::STAT_STAGIAIRE,'Prestataire'=>Collaborateurs::STAT_PRESTATAIRE, 'Sorti'=>Collaborateurs::STAT_SORTI,], 'required' => true
                ))
            ->add('categorie', ChoiceType::class
                ,array('choices'  => ['NON CADRE'=>Collaborateurs::CAT_BFNONCADRE, 'CADRE'=>Collaborateurs::CAT_BFCADRE,], 'required' => true
                ))
            ->add('nom')
            ->add('prenom')
            ->add('qualite')
            ->add('dateNaissance', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('dateEntree', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('dateSortie', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('nbEnfant')
            ->add('nSecuriteSociale')
            ->add('mSalaireBase')
            ->add('mIndemLogement')
            ->add('mIndemTransport')
            ->add('mIndemFonction')
            ->add('mHeureSup')
            ->add('mIndemAutres')
            ->add('mSecuriteSocialeSalarie')
            ->add('mSecuriteSocialePatronal')
            ->add('mImpotSalarie')
            ->add('mTaxePatronale')
            ->add('compteVirement')
            ->add('compteRemunerationDue')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Collaborateurs::class,
        ]);
    }
}
