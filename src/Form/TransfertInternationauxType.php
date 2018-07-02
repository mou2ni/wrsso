<?php

namespace App\Form;

use App\Entity\TransfertInternationaux;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransfertInternationauxType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sens')
            ->add('mTransfert')
            ->add('mFraisHt')
            ->add('mTva')
            ->add('mAutresTaxes')
            ->add('idJourneeCaisse')
            ->add('idSystemTransfert')
            ->add('idPays')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TransfertInternationaux::class,
        ]);
    }
}
