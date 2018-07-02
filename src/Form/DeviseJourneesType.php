<?php

namespace App\Form;

use App\Entity\DeviseJournees;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeviseJourneesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('qteOuv')
            ->add('ecartOuv')
            ->add('qteAchat')
            ->add('qteVente')
            ->add('mCvdAchat')
            ->add('mCvdVente')
            ->add('qteIntercaisse')
            ->add('qteFerm')
            ->add('ecartFerm')
            ->add('idJourneeCaisse')
            ->add('idDevise')
            ->add('idBilletOuv')
            ->add('idBilletFerm')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DeviseJournees::class,
        ]);
    }
}
