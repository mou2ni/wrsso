<?php

namespace App\Form;

use App\Entity\Comptes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ComptesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numCompte')
            ->add('intitule')
            ->add('typeCompte', ChoiceType::class
                ,array('choices'  => ['ORDINAIRE'=>Comptes::ORDINAIRE, 'INTERNE'=>Comptes::INTERNE, 'SALAIRES'=>Comptes::SALAIRE, 'EPARGNE'=>Comptes::EPARGNE], 'required' => true
                ))
            ->add('client')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comptes::class,
        ]);
    }
}
