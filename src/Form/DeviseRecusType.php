<?php

namespace App\Form;

use App\Entity\DeviseRecus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeviseRecusType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateRecu')
            ->add('nom')
            ->add('prenom')
            ->add('typePiece')
            ->add('numPiece')
            ->add('expireLe')
            ->add('paysPiece')
            ->add('motif')
            ->add ('deviseMouvements', CollectionType::class, array (
                'entry_type' => DeviseMouvementsType::class,
                'entry_options' => array('label' => false),
                'allow_add' => true,
                'allow_delete' => true,
            ))->add ('save', SubmitType::class, array('label' => 'Enregistrer'

            ))->add('saveAndAdd', SubmitType::class, array('label' => 'Enregistrer et ajouter'));
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DeviseRecus::class,
        ]);
    }
}
