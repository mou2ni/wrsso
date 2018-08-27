<?php

namespace App\Form;

use App\Entity\DeviseMouvements;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
                'choice_label' => 'libelle',
                'multiple' => false,
                'expanded'=>false))
            ->add('nombre')
            ->add('taux')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DeviseMouvements::class,
        ]);
    }
}
