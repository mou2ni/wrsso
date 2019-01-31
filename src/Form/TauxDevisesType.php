<?php

namespace App\Form;

use App\Entity\Devises;
use App\Entity\DevisesCollection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TauxDevisesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //$data=$options['data'];

        $builder
            ->add('id')
            ->add('devises', CollectionType::class, array(
                'entry_type' => DevisesType::class,
                'allow_add'=>true,
                'allow_delete'=>true,
                'prototype' => true,
                'by_reference' => false,
                'mapped'=> false
                //'attr' => ['class' => 'lignetransfert']
            ))

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            //'data' => 'data'
            'data_class' => DevisesCollection::class,
        ]);
        //$resolver->setRequired(['data']);
    }
}
