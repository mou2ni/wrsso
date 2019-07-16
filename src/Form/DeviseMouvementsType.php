<?php

namespace App\Form;

use App\Entity\DeviseMouvements;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeviseMouvementsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('sens', ChoiceType::class
            //    ,array('choices'  => ['Achat'=>DeviseMouvements::ACHAT, 'Vente'=>DeviseMouvements::VENTE,'Intercaisse'=>DeviseMouvements::INTERCAISSE]
            //    ,'expanded'=>false))
            ->add('devise', EntityType::class, array (
                'class' => 'App\Entity\Devises',
                'choice_label' => 'displayForNegoce',
                'multiple' => false,
                'expanded'=>false))
            ->add('nombre', NumberType::class, array())
            ->add('taux', NumberType::class, array())
            ->add('total', NumberType::class, array('mapped'=>false, 'disabled'=>true))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DeviseMouvements::class,
        ]);
    }
}
