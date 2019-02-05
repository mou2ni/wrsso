<?php

namespace App\Form;

use App\Entity\Caisses;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CaissesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle')
            ->add('code')
            ->add('compteOperation')
            ->add('compteCvDevise')
            ->add('compteIntercaisse')
            ->add('compteAttenteCompense')
            ->add('lastUtilisateur')
            ->add('typeCaisse', ChoiceType::class
                ,array('choices'  => ['GUICHETIER'=>Caisses::GUICHET,'CMD'=>Caisses::MENUDEPENSE, 'TONTINE'=>Caisses::TONTINE,  'COMPENSE'=>Caisses::COMPENSE, 'BANQUE'=>Caisses::BANQUE], 'required' => true
                ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Caisses::class,
        ]);
    }
}
