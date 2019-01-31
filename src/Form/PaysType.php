<?php

namespace App\Form;

use App\Entity\Pays;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaysType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle')
            ->add('zone', ChoiceType::class, ['choices'=>['UEMOA'=>'UEMOA','AUTRES CEDEAO'=>'AUTRES CEDEAO','CEMAC'=>'CEMAC','MAGHREB'=>'MAGHREB','AFRIQUE DU SUD'=>'AFRIQUE DU SUD','USA'=>'USA','EUROPE'=>'EUROPE','INDE'=>'INDE','CHINE'=>'CHINE','BRESIL'=>'BRESIL','AUTRES'=>'AUTRES']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Pays::class,
        ]);
    }
}
