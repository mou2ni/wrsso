<?php

namespace App\Form;

use App\Entity\JourneeCaisses;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JourneeCaissesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateOuv')
            ->add('statut')
            ->add('valeurBillet')
            ->add('soldeElectOuv')
            ->add('ecartOuv')
            ->add('mCvd')
            ->add('mEmissionTrans')
            ->add('mReceptionTrans')
            ->add('mIntercaisse')
            ->add('mRetraitClient')
            ->add('mDepotClient')
            ->add('mCreditDivers')
            ->add('mDetteDivers')
            ->add('dateFerm')
            ->add('idBilletFerm')
            ->add('valeurBilletFerm')
            ->add('SoldeElectFerm')
            ->add('mEcartFerm')
            ->add('idCaisse')
            ->add('idUtilisateur')
            ->add('idJourneeSuivante')
            ->add('idBilletOuv')
            ->add('idSystemElectInventOuv')
            ->add('idSystemElectInventFerm')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => JourneeCaisses::class,
        ]);
    }
}
