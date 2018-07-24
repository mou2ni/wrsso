<?php

namespace App\Form;

use App\Entity\JourneeCaisses;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OuvertureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateOuv')
            //->add('statut')
            ->add('valeurBillet')
            ->add('soldeElectOuv')
            ->add('ecartOuv')
            ->add('mCvd')
            ->add('mCreditDivers')
            ->add('mDetteDivers')
            /*->add('deviseJournee', CollectionType::class, array(
                'entry_type' => DeviseJourneesType::class,
                'allow_add'=>true,
                'allow_delete'=>true
            ))*/
            //->add('idUtilisateur')
            //->add('idJourneeSuivante')
            ->add('idBilletOuv')
            ->add('idSystemElectInventOuv')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => JourneeCaisses::class,
        ]);
    }
}
