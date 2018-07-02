<?php

namespace App\Form;

use App\Entity\DeviseMouvements;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeviseMouvementsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sens')
            ->add('nombre')
            ->add('mCvd')
            ->add('idJourneeCaisse')
            ->add('idDevise')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DeviseMouvements::class,
        ]);
    }
}
