<?php

namespace App\Form;

use App\Entity\RecetteDepenseRubriques;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecetteDepenseRubriquesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mPart')
            ->add('recetteDepense')
            ->add('rubriqueAnalyse')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RecetteDepenseRubriques::class,
        ]);
    }
}
