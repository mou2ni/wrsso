<?php

namespace App\Form;

use App\Entity\JourneeCaisses;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OuvertureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('billetOuv')
            ->remove('systemElectInventOuv')
            ->remove('billetFerm')
            ->remove('systemElectInventFerm')
            ->remove('transfertInternationaux')
            ->remove('mLiquiditeFerm')
            ->remove('mSoldeElectFerm')
            ->remove('mIntercaisses')
            ->remove('mEmissionTrans')
            ->remove('mReceptionTrans')
            ->remove('mRetraitClient')
            ->remove('mDepotClient')
            ->remove('mEcartFerm')
        ;
    }

    public function getParent(){
        return JourneeCaissesType::class;
    }
}
