<?php

namespace App\Form;

use App\Entity\CompenseLignes;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompenseLignesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mEnvoiAttendu', NumberType::class, array('disabled' => true,'grouping'=>3,'scale'=>0, 'attr'=>array('class'=>'text-right')))
            ->add('mReceptionAttendu', NumberType::class, array('disabled' => true,'grouping'=>3,'scale'=>0, 'attr'=>array('class'=>'text-right')))
            ->add('mEnvoiCompense')
            ->add('mReceptionCompense')
            //->add('compense')
            ->add('systemTransfert', EntityType::class, array(
                'class' => 'App\Entity\SystemTransfert',
                'disabled' => true))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CompenseLignes::class,
        ]);
    }
}
