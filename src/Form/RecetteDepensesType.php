<?php

namespace App\Form;

use App\Entity\Agences;
use App\Entity\Comptes;
use App\Entity\RecetteDepenses;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecetteDepensesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $isComptable=$options['isComptable'];
        $isComptant=$options['isComptant'];
        $statut=$options['statut'];
        
        $builder
            ->add('dateOperation', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('libelle');
        if ($statut==RecetteDepenses::STAT_INITIAL)
            $builder->add('mSaisie');

        if ($isComptable){
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
                    }));
            if ($statut!=RecetteDepenses::STAT_COMPTA)
                $builder
                    ->add('typeOperationComptable', EntityType::class, array (
                    'class' => 'App\Entity\TypeOperationComptables',
                    'choice_label' => 'libelle',
                    'multiple' => false,
                    'expanded'=>false,))
            ;
            if ($isComptant)
                if ($statut!=RecetteDepenses::STAT_COMPTA)
                    $builder
                    ->add('journeeCaisse', EntityType::class, array (
                        'class' => 'App\Entity\JourneeCaisses',
                        'multiple' => false,
                        'expanded'=>false,
                        'query_builder' => function (EntityRepository $er) {
                            return $er->getOpenJourneeCaisseRDQb();
                        }
                    ))
                ;
            else
                if ($statut!=RecetteDepenses::STAT_COMPTA)
                    $builder
                    ->add('compteTier', EntityType::class, array (
                        'class' => 'App\Entity\Comptes',
                        'choice_label' => function (Comptes $compte) {
                            return $compte->getNumCompteIntitule();},
                        'multiple' => false,
                        'expanded'=>false,
                        'query_builder' => function (EntityRepository $er) {
                            return $er->getCompteTierAtermeQb();
                        }
                    ))
                ;

            $builder
                ->add('numDocumentCompta');
        }


            /*->add('dateOperation', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('typeOperationComptable', EntityType::class, array (
                'class' => 'App\Entity\TypeOperationComptables',
                'choice_label' => 'libelle',
                'multiple' => false,
                'expanded'=>false,))
            ->add('compteTier', EntityType::class, array (
                'class' => 'App\Entity\Comptes',
                'choice_label' => function (Comptes $compte) {
                    return $compte->getNumCompteIntitule();},
                'multiple' => false,
                'expanded'=>false,
                'query_builder' => function (EntityRepository $er) {
                    return $er->getCompteContrePartieDepenseRecettesQb();
                }
            ))
            ->add('libelle')
            ->add('mSaisie')
            //->add('estComptant')
            ->add('numDocumentCompta')
            ->add('agence', EntityType::class, array (
                'class' => 'App\Entity\Agences',
                'choice_label' => function (Agences $agence) {
                    return $agence->getDisplayName();},
                'multiple' => false,
                'expanded'=>false,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('a')
                        ->orderBy('a.code', 'ASC');
                }))*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RecetteDepenses::class,
            'isComptable'=>false,
            'isComptant'=>true,
            'statut'=>RecetteDepenses::STAT_INITIAL,
        ]);
        $resolver->setRequired(['isComptable','isComptant']);

    }
}
