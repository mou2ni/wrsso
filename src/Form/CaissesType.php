<?php

namespace App\Form;

use App\Entity\Agences;
use App\Entity\Caisses;
use App\Entity\Comptes;
use App\Repository\ComptesRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CaissesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle')
            ->add('code')
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
            ->add('compteOperation', EntityType::class, array (
                'class' => 'App\Entity\Comptes',
                'choice_label' => function (Comptes $compte) {
                    return $compte->getNumCompteIntitule();},
                'multiple' => false,
                'expanded'=>false,
                'query_builder' => function (ComptesRepository $er) {
                    return $er->getCompteTresorerieQb();
                }))
            ->add('compteCvDevise', EntityType::class, array (
                'class' => 'App\Entity\Comptes',
                'choice_label' => function (Comptes $compte) {
                    return $compte->getNumCompteIntitule();},
                'multiple' => false,
                'expanded'=>false,
                'query_builder' => function (ComptesRepository $er) {
                    return $er->getCompteTresorerieQb();
                }))
            ->add('compteIntercaisse', EntityType::class, array (
                'class' => 'App\Entity\Comptes',
                'choice_label' => function (Comptes $compte) {
                    return $compte->getNumCompteIntitule();},
                'multiple' => false,
                'expanded'=>false,
                'query_builder'  => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->where('c.numCompte like \'588%\'')
                        ->orderBy('c.numCompte', 'ASC');
                }))
            ->add('compteAttenteCompense', EntityType::class, array (
                'class' => 'App\Entity\Comptes',
                'choice_label' => function (Comptes $compte) {
                    return $compte->getNumCompteIntitule();},
                'multiple' => false,
                'expanded'=>false,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->where('c.numCompte like \'585%\' ')
                        ->orderBy('c.numCompte', 'ASC');
                    }))
            ->add('journalComptable')
            ->add('comptaDetail')
            ->add('dispoGuichet')
            ->add('lastUtilisateur')
            ->add('typeCaisse', ChoiceType::class
                ,array('choices'  => ['GUICHETIER'=>Caisses::GUICHET,'CMD'=>Caisses::MENUDEPENSE, 'TONTINE'=>Caisses::TONTINE,  'COMPENSE'=>Caisses::COMPENSE, 'BANQUE'=>Caisses::BANQUE, 'RETOUR CLIENT'=>Caisses::TYP_RETOUR], 'required' => true
                ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Caisses::class,
        ]);
    }
}
