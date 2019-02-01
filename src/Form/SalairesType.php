<?php

namespace App\Form;

use App\Entity\LigneSalaires;
use App\Entity\Salaires;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SalairesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('periodeSalaire')
            ->add('mNetTotal')
            ->add('mTaxeTotal')
            ->add('mImpotTotal')
            ->add('mSecuriteSocialTotal')
            ->add('ligneSalaires', CollectionType::class, array(
                'entry_type' => LigneSalaires::class,
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
