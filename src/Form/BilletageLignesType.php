<?php

namespace App\Form;

use App\Entity\BilletageLignes;
use Doctrine\DBAL\Types\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BilletageLignesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nbBillet', NumberType::class,array('grouping'=>3,'scale'=>0))
            ->add('valeurLigne', NumberType::class,array('mapped'=>false,'grouping'=>3,'scale'=>0, 'disabled'=>true))
            //->add('billetages')
            ->add('valeurBillet', NumberType::class,array('grouping'=>3,'scale'=>0, 'disabled'=>true))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BilletageLignes::class,
        ]);
    }
}
