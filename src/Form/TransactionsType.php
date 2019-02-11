<?php

namespace App\Form;

use App\Entity\TransactionComptes;
use App\Entity\Transactions;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransactionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateTransaction')
            ->add('libelle')
            ->add('numPiece')
            ->add('transactionComptes', CollectionType::class, array(
                'entry_type' => TransactionComptes::class,
                'allow_add'=>true,
                'allow_delete'=>true,
                'by_reference' => false,
                'attr' => ['class' => 'collections-tag']
            ))
            //->add('utilisateur')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Transactions::class,
        ]);
    }
}
