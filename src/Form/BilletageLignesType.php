<?php

namespace App\Form;

use App\Entity\BilletageLignes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BilletageLignesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nbBillet')
            ->add('valeurLigne')
            ->add('idBilletage', BilletagesType::class)
            ->add('valeurBillet')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BilletageLignes::class,
        ]);
    }
}
