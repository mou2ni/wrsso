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
            ->add('libelle')
            //->add('mDette',NumberType::class,array('grouping'=>3,'scale'=>0, 'constraints'=>[new \Symfony\Component\Validator\Constraints\GreaterThan(0)]))
            //->add('mCredit',NumberType::class,array('grouping'=>3,'scale'=>0, 'constraints'=>[new \Symfony\Component\Validator\Constraints\GreaterThan(0)]))
            ->add('mSaisie',NumberType::class,array('grouping'=>3,'scale'=>0))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DetteCreditDivers::class,
        ]);
    }
}
