<?php

namespace App\Form;

use App\Entity\Caisses;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class trimestreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('an', DateTimeType::class)
            ->add('trimestre', ChoiceType::class, ['choices'=>['1er trimestre'=>1,'2em trimestre'=>2,'3em trimestre'=>3,'4em trimestre'=>4]]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            //'data_class' => Caisses::class,
        ]);
    }
}
