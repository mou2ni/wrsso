<?php

namespace App\Form;

use App\Entity\DepotRetrait;
use App\Entity\TransactionComptes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\GreaterThanValidator;
use Webmozart\Assert\Assert;



class DepotRetraitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numCompte')
            ->add('mDebit',NumberType::class,array('grouping'=>3,'scale'=>0, 'constraints'=>[new \Symfony\Component\Validator\Constraints\GreaterThan(0)]))
            ->add('mCredit',NumberType::class,array('grouping'=>3,'scale'=>0, 'constraints'=>[new \Symfony\Component\Validator\Constraints\GreaterThan(0)]))
            //->add('transaction', TransactionsType::class)
            ->add('libele', TextareaType::class)
            //->add('compte')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DepotRetrait::class,
            'block_name' => 'depot_retrait'
        ]);
    }
}
