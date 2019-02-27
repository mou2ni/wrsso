<?php

namespace App\Form;

use App\Entity\Caisses;
use App\Entity\Comptes;
use App\Repository\ComptesRepository;
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
                'query_builder' => function (ComptesRepository $er) {
                    return $er->getCompteTresorerieQb();
                }))
            ->add('compteAttenteCompense', EntityType::class, array (
                'class' => 'App\Entity\Comptes',
                'choice_label' => function (Comptes $compte) {
                    return $compte->getNumCompteIntitule();},
                'multiple' => false,
                'expanded'=>false,
                'query_builder' => function (ComptesRepository $er) {
                    return $er->getCompteTresorerieQb();
                }))
            ->add('journalComptable')
            ->add('comptaDetail')
            ->add('lastUtilisateur')
            ->add('typeCaisse', ChoiceType::class
                ,array('choices'  => ['GUICHETIER'=>Caisses::GUICHET,'CMD'=>Caisses::MENUDEPENSE, 'TONTINE'=>Caisses::TONTINE,  'COMPENSE'=>Caisses::COMPENSE, 'BANQUE'=>Caisses::BANQUE], 'required' => true
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
