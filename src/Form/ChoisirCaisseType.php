<?php

namespace App\Form;

use App\Entity\DepotRetrait;
use App\Entity\TransactionComptes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\GreaterThanValidator;
use Webmozart\Assert\Assert;



class ChoisirCaisseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('billetOuv')
            ->remove('mLiquiditeOuv')
            ->remove('systemElectInventOuv')
            ->remove('mSoldeElectOuv')
            ->remove('mEcartOuv')
            ->remove('billetFerm')
            ->remove('mLiquiditeFerm')
            ->remove('systemElectInventFerm')
            ->remove('mSoldeElectFerm')
            ->remove('mCreditDiversOuv')
            ->remove('mDetteDiversFerm')
            ->remove('mCreditDiversFerm')
            ->remove('mIntercaisses')
            ->remove('transfertInternationaux')
            ->remove('mEmissionTrans')
            ->remove('mReceptionTrans')
            ->remove('mCvd')
            ->remove('mRetraitClient')
            ->remove('mDepotClient')
            ->remove('mouvementFond')
            ->remove('mEcartFerm')
            ->remove('deviseJournees')
            ->remove('save')
            ->remove('simulate')
        ;
    }

    public function getParent(){
        return JourneeCaissesType::class;
    }
}
