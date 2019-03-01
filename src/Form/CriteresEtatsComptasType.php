<?php

namespace App\Form;

use App\Entity\Comptes;
use App\Entity\CriteresEtatsComptas;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CriteresEtatsComptasType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('compteDebut', EntityType::class, array (
                'class' => 'App\Entity\Comptes',
                'choice_label' => function (Comptes $compte) {
                    return $compte->getNumCompteIntitule();},
                'multiple' => false,
                'expanded'=>false,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.numCompte', 'ASC');
                }
            ))
            ->add('compteFin', EntityType::class, array (
                'class' => 'App\Entity\Comptes',
                'choice_label' => function (Comptes $compte) {
                    return $compte->getNumCompteIntitule();},
                'multiple' => false,
                'expanded'=>false,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.numCompte', 'ASC');
                }
            ))
            ->add('dateDebut', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('dateFin', DateType::class, [
                'widget' => 'single_text'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CriteresEtatsComptas::class,
        ]);
    }
}
