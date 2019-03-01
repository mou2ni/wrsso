<?php

namespace App\Form;

use App\Entity\Comptes;
use App\Entity\DepotRetraits;
use App\Repository\ComptesRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DepotRetraitsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numCompteSaisie')
            ->add('libelle')
            ->add('mDepot')
            ->add('mRetrait')
            ->add('compteClient', EntityType::class, array (
                //'mapped'=>false,
                'class' => 'App\Entity\Comptes',
                'choice_label' => function (Comptes $compte) {
                        return $compte->getNumCompteIntitule();},
                'multiple' => false,
                'expanded'=>false,
                'query_builder' => function(ComptesRepository $repository){
                    return $repository->getCompteEncDecQb();
                }
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DepotRetraits::class,
        ]);
    }
}
