<?php

namespace App\Form;

use App\Entity\JourneeCaisses;
use function PHPSTORM_META\type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\CurrencyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JourneeCaissesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('caisse')
            ->add('billetOuv')
            ->add('mLiquiditeOuv', NumberType::class,array('disabled'=>true))
            ->add('systemElectInventOuv')
            ->add('mSoldeElectOuv' , NumberType::class,array('disabled'=>true))
            ->add('mEcartOuv', NumberType::class,array('disabled'=>true))
            ->add('billetFerm')
            ->add('mLiquiditeFerm' , NumberType::class,array('disabled'=>true))
            ->add('systemElectInventFerm')
            ->add('mSoldeElectFerm', NumberType::class,array('disabled'=>true))
            ->add('mDetteDiversOuv', NumberType::class,array('disabled'=>true))
            ->add('mCreditDiversOuv', NumberType::class,array('disabled'=>true))
            ->add('mDetteDiversFerm', NumberType::class,array('disabled'=>true))
            ->add('mCreditDiversFerm', NumberType::class,array('disabled'=>true))
            ->add('mIntercaisses', NumberType::class,array('disabled'=>true))
            ->add('transfertInternationaux')
            ->add('mEmissionTrans', NumberType::class,array('disabled'=>true))
            ->add('mReceptionTrans', NumberType::class,array('disabled'=>true))
            ->add('mCvd', NumberType::class,array('disabled'=>true))
            ->add('mRetraitClient', NumberType::class,array('disabled'=>true))
            ->add('mDepotClient', NumberType::class,array('disabled'=>true))
            ->add('mEcartFerm', NumberType::class,array('disabled'=>true))
            ->add('deviseJournees', CollectionType::class, array(
                    'disabled'=>true,
                    'entry_type' => DeviseJourneesType::class,
                    'entry_options' => array('label' => false),
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                    'label' => false,
                )
            )->add ('save', SubmitType::class, array('label' => 'Enregistrer'
            ))->add('simulate', SubmitType::class, array('label' => 'Simuler'
            ))
        ;




    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => JourneeCaisses::class,
        ]);
    }
}
