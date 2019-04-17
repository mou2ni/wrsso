<?php

namespace App\Form;

use App\Entity\LigneSalaires;
use App\Entity\Salaires;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SalairesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $today=new \DateTime();
        $annee=$today->format('Y');
        $builder
            ->add('dateSalaire', DateType::class, [
                'widget' => 'single_text'
            ])
            /*->add('periodeSalaire', ChoiceType::class
                ,array('choices'  => ['Janvier'=> 'Jan-'.$annee, 'Février'=>'Fev-'.$annee,'Mars'=>'Mar-'.$annee,'Avril'=>'Avri-'.$annee,
                    'Mai'=>'Mai-'.$annee,'Juin'=>'Juin-'.$annee,'Juillet'=>'Juil-'.$annee,'Aout'=>'Aout-'.$annee,'Septembre'=>'Sept-'.$annee,
                    'Octobre'=>'Oct-'.$annee,'Novembre'=>'Nov-'.$annee,'Décembre'=>'Dec-'.$annee,], 'required' => true
                ))*/
            ->add('periode', DateType::class,['label'=>'Periode', 'widget'=>'single_text'])
            ->add('mBrutTotal')
            ->add('mTaxeTotal')
            ->add('mImpotTotal')
            ->add('mSecuriteSocialSalarie')
            ->add('mSecuriteSocialPatronal')
            ->add('ligneSalaires', CollectionType::class, array(
                'entry_type' => LigneSalairesType::class,
                'allow_add'=>true,
                'allow_delete'=>true,
                'by_reference' => false,
                'attr' => ['class' => 'collections-tag']
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Salaires::class,
        ]);
    }
}
