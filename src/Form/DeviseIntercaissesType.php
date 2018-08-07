<?php

namespace App\Form;

use App\Entity\DeviseIntercaisses;
use App\Entity\JourneeCaisses;
use App\Repository\JourneeCaissesRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeviseIntercaissesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $statut=JourneeCaisses::OUVERT;
        //$statut='T';

        $builder
            ->add('journeeCaisseSource', EntityType::class, array (
                'class' => 'App\Entity\JourneeCaisses',
                'choice_label' => 'journeeCaisse',
                'multiple' => false,
                'expanded'=>false,
                'query_builder' => function(JourneeCaissesRepository $repository) use($statut) {
                    return $repository->getOpenJourneeCaisseQb($statut);
                }
            ))->add ('deviseMouvements', CollectionType::class, array (
                'entry_type' => DeviseMvtIntercaisseType::class,
                'entry_options' => array('label' => false),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => false,
            ))->add ('save_and_close', SubmitType::class, array('label' => 'Enregistrer et fermer'
            ))->add('reset', SubmitType::class, array('label' => 'Effacer'
            ))->add('close', SubmitType::class, array('label' => 'Fermer'
            ));
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DeviseIntercaisses::class,
        ]);
    }
}
