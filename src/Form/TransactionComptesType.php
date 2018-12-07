<?php

namespace App\Form;

use App\Entity\TransactionComptes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransactionComptesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numCompte')
            ->add('mDebit', NumberType::class,array('grouping'=>3,'scale'=>0, 'constraints'=>[new \Symfony\Component\Validator\Constraints\GreaterThanOrEqual(0)]))
            ->add('mCredit', NumberType::class,array('grouping'=>3,'scale'=>0, 'constraints'=>[new \Symfony\Component\Validator\Constraints\GreaterThanOrEqual(0)]))
            ->add('transaction', TransactionsType::class)
            ->add('compte')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TransactionComptes::class,
        ]);
    }
}
