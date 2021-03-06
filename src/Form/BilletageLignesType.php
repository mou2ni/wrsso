<?php

namespace App\Form;

use App\Entity\BilletageLignes;
use Doctrine\DBAL\Types\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Intl\NumberFormatter\NumberFormatter;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BilletageLignesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('valeurBillet', NumberType::class, ['disabled'=>true,'grouping'=>3,'scale'=>0, 'attr'=>['class'=>"text-right"]])
            ->add('nbBillet', NumberType::class,['attr'=>['class'=>"text-center"]])
            ->add('totalLigne', NumberType::class, ['disabled'=>true,'grouping'=>3,'scale'=>0, 'attr'=>['class'=>"text-right"]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BilletageLignes::class,
        ]);
    }
}
