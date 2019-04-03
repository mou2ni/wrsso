<?php

namespace App\Form;

use App\Entity\ApproVersements;
use App\Repository\JourneeCaissesRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ApproVersementsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('journeeCaissePartenaire', EntityType::class, array (
                //'mapped'=>false,
                'class' => 'App\Entity\JourneeCaisses',
                'choice_label' => 'journeeCaisse',
                'multiple' => false,
                'expanded'=>false,
                'placeholder'=>'Selectionnez',
                'query_builder' => function(JourneeCaissesRepository $repository){
                    return $repository->getOpenBanqueQb();
                }
            ))
            ->add('mSaisie')
            ->add('libelle')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ApproVersements::class,
        ]);
    }
}
