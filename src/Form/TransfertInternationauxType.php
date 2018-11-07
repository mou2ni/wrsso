<?php

namespace App\Form;

use App\Entity\TransfertInternationaux;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransfertInternationauxType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sens', ChoiceType::class, array(
                'choices'  => array(
                    'Envoi' => 1,
                    'Reception' => 2)))
            ->add('mTransfert', NumberType::class,array('grouping'=>3,'scale'=>0, 'empty_data'=>0, 'attr'=>['class'=>'mtransfert']))
            ->add('mTransfertTTC', NumberType::class,array('grouping'=>3,'scale'=>0, 'attr'=>['class'=>';transfertttc']))
            ->add('mFraisHt', NumberType::class,array('grouping'=>3,'scale'=>0, 'attr'=>['class'=>'mfraisht']))
            ->add('mTva', NumberType::class,array('grouping'=>3,'scale'=>0, 'attr'=>['class'=>'mtva']))
            ->add('mAutresTaxes', NumberType::class,array('grouping'=>3,'scale'=>0, 'attr'=>['class'=>'mautrestaxes', 'placeholder'=>0]))
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
