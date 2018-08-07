<?php

namespace App\Form;

use App\Entity\DeviseTmpMouvements;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeviseTmpMouvementsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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
            'data_class' => DeviseTmpMouvements::class,
        ]);
    }
}
