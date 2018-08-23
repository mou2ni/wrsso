<?php

namespace App\Form;

use App\Entity\JourneeCaisses;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CurrencyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JourneeCaissesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('caisse')
            ->add('billetOuv')
            ->add('mLiquiditeOuv')
            ->add('systemElectInventOuv')
            ->add('mSoldeElectOuv')
            ->add('mEcartOuv', NumberType::class,array('disabled'=>true))
            ->add('billetFerm')
            ->add('mLiquiditeFerm')
            ->add('systemElectInventFerm')
            ->add('mSoldeElectFerm')
            ->add('mDetteDivers')
            ->add('mCreditDivers')
            ->add('mIntercaisses')
            ->add('transfertInternationaux')
            ->add('mEmissionTrans')
            ->add('mReceptionTrans')
            ->add('mCvd')
            ->add('mRetraitClient')
            ->add('mDepotClient')
            ->add('mEcartFerm', NumberType::class,array('disabled'=>true))
        ;




    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => JourneeCaisses::class,
        ]);
    }
}
