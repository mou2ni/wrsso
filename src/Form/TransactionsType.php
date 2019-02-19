<?php

namespace App\Form;

use App\Entity\JournauxComptables;
use App\Entity\TransactionComptes;
use App\Entity\Transactions;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransactionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('journauxComptable', EntityType::class, array (
                //'mapped'=>false,
                'class' => 'App\Entity\JournauxComptables',
                'choice_label' => function (JournauxComptables $journal) {
                    return $journal->getDisplayName();},
                'multiple' => false,
                'expanded'=>false,
            ))
            ->add('dateTransaction', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('libelle')
            ->add('numPiece')
            ->add('transactionComptes', CollectionType::class, array(
                'entry_type' => TransactionComptesType::class,
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
