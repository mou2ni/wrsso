<?php

namespace App\Form;

use App\Entity\DetteCreditDivers;
use Doctrine\DBAL\Types\FloatType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\DateTime;

class DetteCreditDiversType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('operation', ChoiceType::class, array('mapped'=>false, 'choices'=>['Dette'=>1, 'Crédit'=>2], 'data'=>1, 'expanded'=>true))
            ->add('libelle')
            //->add('statut')
            ->add('montant',NumberType::class, array('required'=>true, 'mapped'=>false, 'grouping'=>3,'scale'=>0, 'constraints'=>[new \Symfony\Component\Validator\Constraints\GreaterThanOrEqual(0)]))
            //->add('mDette',NumberType::class,array('grouping'=>3,'scale'=>0, 'constraints'=>[new \Symfony\Component\Validator\Constraints\GreaterThanOrEqual(0)]))
            //->add('caisse')
            //->add('journeeCaisse')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DetteCreditDivers::class,
        ]);
    }
}
