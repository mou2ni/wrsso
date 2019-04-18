<?php

namespace App\Form;

use App\Entity\Agences;
use App\Entity\LigneSalaires;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LigneSalairesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('collaborateur')
            ->add('mSalaireBase')
            ->add('mIndemLogement')
            ->add('mIndemTransport')
            ->add('mIndemFonction')
            ->add('mIndemAutres')
            ->add('mHeureSup')
            ->add('mSecuriteSocialeSalarie')
            ->add('mSecuriteSocialePatronal')
            ->add('mImpotSalarie')
            ->add('mTaxePatronale')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => LigneSalaires::class,
        ]);
    }
}
