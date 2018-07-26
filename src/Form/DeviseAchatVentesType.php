<?php

namespace App\Form;

use App\Entity\DeviseAchatVentes;
use App\Entity\Devises;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeviseAchatVentesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('devise', EntityType::class, array (
                'class' => 'App\Entity\Devises',
                'choice_label' => 'libelle',
                'multiple' => false,
                'expanded'=>false))
            ->add('sens', ChoiceType::class
                ,array('choices'  => ['Achat'=>'a', 'Vente'=>'v']
                        ,'expanded'=>true))
            ->add('nombre')
            ->add('taux')
            ->add('dateRecu', DateTimeType::class)
            ->add('nomPrenom', TextType::class)
            ->add('typePiece', ChoiceType::class
                ,array('choices'  => ['CNI'=>1, 'Passport'=>2, 'Autre'=>3]))
            ->add('numPiece', TextType::class)
            ->add('expireLe', DateTimeType::class)
            ->add('motif', TextareaType::class)
        ;
    }
    

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DeviseAchatVentes::class,
        ]);
    }
}
