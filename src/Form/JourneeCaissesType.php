<?php

namespace App\Form;

use App\Entity\Caisses;
use App\Entity\JourneeCaisses;
use App\Repository\CaissesRepository;
use Doctrine\ORM\EntityRepository;
use function PHPSTORM_META\type;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\CurrencyType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JourneeCaissesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('caisse', ChoiceType::class, array())
            ->add('caisse', EntityType::class, array(
                'class' => Caisses::class,
                'query_builder' => function (CaissesRepository $repository) {
                    return $repository->getClosedCaisseQb();
                },
                'choice_label' => 'code',
            ))
            ->add('billetOuv')
            ->add('mLiquiditeOuv', NumberType::class,array('grouping'=>3,'scale'=>0,'disabled'=>true))
            ->add('systemElectInventOuv')
            ->add('mSoldeElectOuv' , NumberType::class,array('grouping'=>3,'scale'=>0,'disabled'=>true))
            ->add('mEcartOuv', NumberType::class,array('grouping'=>3,'scale'=>0,'disabled'=>true))
            ->add('billetFerm')
            ->add('mLiquiditeFerm' , NumberType::class,array('grouping'=>3,'scale'=>0,'disabled'=>true))
            ->add('systemElectInventFerm')
            ->add('mSoldeElectFerm', NumberType::class,array('grouping'=>3,'scale'=>0,'disabled'=>true))
            ->add('mDetteDiversOuv', NumberType::class,array('grouping'=>3,'scale'=>0,'disabled'=>true))
            ->add('mCreditDiversOuv', NumberType::class,array('grouping'=>3,'scale'=>0,'disabled'=>true))
            ->add('mDetteDiversFerm', NumberType::class,array('grouping'=>3,'scale'=>0,'disabled'=>true))
            ->add('mCreditDiversFerm', NumberType::class,array('grouping'=>3,'scale'=>0,'disabled'=>true))
            ->add('mIntercaisses', NumberType::class,array('grouping'=>3,'scale'=>0,'disabled'=>true))
            ->add('transfertInternationaux')
            ->add('mEmissionTrans', NumberType::class,array('grouping'=>3,'scale'=>0,'disabled'=>true))
            ->add('mReceptionTrans', NumberType::class,array('grouping'=>3,'scale'=>0,'disabled'=>true))
            ->add('mCvd', NumberType::class,array('grouping'=>3,'scale'=>0,'disabled'=>true))
            ->add('mRetraitClient', NumberType::class,array('grouping'=>3,'scale'=>0,'disabled'=>true))
            ->add('mDepotClient', NumberType::class,array('grouping'=>3,'scale'=>0,'disabled'=>true))
            ->add('mouvementFond', NumberType::class,array('grouping'=>3,'scale'=>0,'disabled'=>true))
            ->add('mEcartFerm', NumberType::class,array('grouping'=>3,'scale'=>0,'disabled'=>true))
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
