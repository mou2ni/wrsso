<?php

namespace App\Form;

use App\Entity\InterCaisses;
use App\Repository\JourneeCaissesRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InterCaissesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mIntercaisse')
            //->add('statut')
            ->add('observations', TextareaType::class)
            ->add('journeeCaisseSortant', EntityType::class, array (
                'class' => 'App\Entity\JourneeCaisses',
                'choice_label' => 'journeeCaisse',
                'multiple' => false,
                'expanded'=>false,
                'query_builder' => function(JourneeCaissesRepository $repository) {
                    return $repository->getOpenJourneeCaisseQb();
                }
            ))
            //->add('journeeCaisseEntrant'/*, ChoiceType::class, array('placeholder' => 'Choisir la caisse')*/)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => InterCaisses::class,
        ]);
    }
}
