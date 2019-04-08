<?php

namespace App\Form;

use App\Entity\ParamComptables;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParamComptablesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codeStructure')
            ->add('compteIntercaisse')
            ->add('compteContreValeurDevise')
            ->add('compteCompense')
            ->add('compteChargeBaseSalaire')
            ->add('compteChargeLogeSalaire')
            ->add('compteChargeTranspSalaire')
            ->add('compteChargeFonctSalaire')
            ->add('compteChargeIndemSalaire')
            ->add('compteChargeCotiPatronale')
            ->add('compteTaxeSalaire')
            ->add('compteOrgaSocial')
            ->add('compteOrgaImpotSalaire')
            ->add('compteOrgaTaxeSalaire')
            ->add('compteOrgaTaxeFactClt')
            ->add('compteOrgaTaxeFactFseur')
            ->add('compteRemunerationDue')
            ->add('compteEcartCaisse')
            ->add('compteDiversCharge')
            ->add('compteDiversProduits')
            ->add('journalPaye')
            ->add('journalVente')
            ->add('journalAchat')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ParamComptables::class,
        ]);
    }
}
