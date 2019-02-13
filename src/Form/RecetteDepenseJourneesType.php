<?php

namespace App\Form;

use App\Entity\JourneeCaisses;
use Doctrine\DBAL\Types\BooleanType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecetteDepenseJourneesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //$estComptant=$options['estComptant'];

        $builder
            ->add('mRecette', NumberType::class,array('grouping'=>3,'scale'=>0))
            ->add('mDepense', NumberType::class,array('grouping'=>3,'scale'=>0))
            ->add('recetteDepenses', CollectionType::class, array(
                'entry_type' => RecetteDepensesComptantsType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'attr' => ['class' => 'collections-tag']
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => JourneeCaisses::class,
            //'estComptant'=>true,
        ]);
    }
}
