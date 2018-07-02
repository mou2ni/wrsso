<?php

namespace App\Form;

use App\Entity\ParamComptables;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParamComptablesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('idCompteIntercaisse')
            ->add('idCompteContreValeurDevise')
            ->add('idCompteCompense')
            ->add('idCompteChargeSalaireNet')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ParamComptables::class,
        ]);
    }
}
