<?php

namespace App\Form;

use App\Entity\InterCaisses;
use App\Repository\JourneeCaissesRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InterCaissesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $dateComptable=$options['dateComptable'];
        $myJournee=$options['myJournee'];
        $builder
             ->add('journeeCaissePartenaire', EntityType::class, array (
                //'mapped'=>false,
                'class' => 'App\Entity\JourneeCaisses',
                'choice_label' => 'journeeCaisse',
                'multiple' => false,
                'expanded'=>false,
                 'placeholder'=>'Selectionnez',
                'query_builder' => function(JourneeCaissesRepository $repository) use ($dateComptable,$myJournee) {
                    return $repository->getOpenJourneeCaisseQb($dateComptable,$myJournee);
                }
            ))
            ->add('mIntercaisse',NumberType::class, array('required'=>true, 'grouping'=>3,'scale'=>0))
            ->add('observations', TextareaType::class)
      ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => InterCaisses::class,
            'dateComptable'=>null,
            'myJournee'=>null,
        ]);
        $resolver->setRequired(['dateComptable','myJournee']);
        //$resolver->setRequired([]);
    }
}
