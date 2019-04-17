<?php

namespace App\Form;

use App\Entity\RecetteDepenses;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecetteDepensesCorrectionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('compteTier')
            ->remove('estComptant')
            ->remove('typeOperationComptable')
            ->remove('numDocumentCompta')
            ->remove('mSaisie')
            ;
    }

    public function getParent(){
        return RecetteDepensesType::class;
    }
}
