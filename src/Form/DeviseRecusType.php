<?php

namespace App\Form;

use App\Entity\DeviseRecus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeviseRecusType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateRecu', DateType::class, array('required' => true))
            ->add('nom', TextType::class, array('required' => true))
            ->add('prenom', TextType::class, array('required' => true))
            ->add('typePiece', ChoiceType::class
                ,array('choices'  => ['Carte nationale'=>'CNI', 'Passport'=>'Passort', 'Autres'=>'Autres'], 'required' => true ))
            ->add('numPiece', TextType::class, array('required' => true))
            ->add('expireLe', DateType::class, array('required' => true))
            ->add('paysPiece', EntityType::class, array (
                'class' => 'App\Entity\Pays',
                'choice_label' => 'libelle',
                'multiple' => false,
                'expanded'=>false
            ))->add('motif', TextareaType::class, array('required' => false))
            ->add ('deviseMouvements', CollectionType::class, array (
                'entry_type' => DeviseMouvementsType::class,
                'entry_options' => array('label' => false),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => false,
            ))->add ('save_and_new', SubmitType::class, array('label' => 'Enregistrer Ajouter'
            ))->add('save_and_print', SubmitType::class, array('label' => 'Enregistrer et imprimer'
            ))->add('reset', SubmitType::class, array('label' => 'Effacer'
            ))->add('close', SubmitType::class, array('label' => 'Fermer'
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DeviseRecus::class,
        ]);
    }
}