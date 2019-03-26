<?php

namespace App\Form;

use App\Entity\Compenses;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompenseCollectionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('caisse')
            ->add('compenseLignes', CollectionType::class, array(
                'entry_type' => CompenseLignesType::class,
                'allow_add'=>true,
                'allow_delete'=>true,
                'by_reference' => false,
                'attr' => ['class' => 'collections-tag']
            ))
        ;
    }

    public function getParent(){
        return CompensesType::class;
    }
}
